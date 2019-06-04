import { Component, OnInit } from '@angular/core';
import { QueueService } from 'src/app/services/queue.service';
import { HelperService } from 'src/app/services/helper.service';
import { AuthService } from 'src/app/services/auth.service';
import { LineService } from 'src/app/services/line.service';
import { Router } from '@angular/router';
import { environment } from 'src/environments/environment';
import { PrinterService } from 'src/app/services/printer.service';
declare var jQuery;
declare var window:any;

@Component({
  selector: 'app-issue-q',
  templateUrl: './issue-q.component.html',
  styleUrls: ['./issue-q.component.css']
})
export class IssueQComponent implements OnInit {
  public issue:any = {};
  public loading:boolean = false;
  public loggedInCompany:any;
  public lines:any = [];
  public sline:any = [];
  public issueNo:string;
  public issueDate:any;
  public companyName:string;
  public arrivalTime:string;
  public note:string;
  public tags:any = [];
  public tagged:any = {};
  public printers:any = [];

  constructor(
    private queueSvc:QueueService, 
    private helper:HelperService, 
    private auth:AuthService, 
    private lineSvc:LineService,
    private router:Router,
    private printerSvc: PrinterService
    ) { 
    this.loggedInCompany = this.auth.getLoginCompany();
    this.companyName = this.loggedInCompany.name;

   this.getLines();

  }

  getLines(){

    this.lineSvc.getLineByCompany(this.loggedInCompany.id).subscribe((res:any)=>{
      this.lines = res.data.lines;
      this.tagged = res.data.tagged;
      //jQuery(".form-check label,.form-radio label").append('<i class="input-helper"></i>');
    },
    (err)=>{
      this.loading = false;
      if ( err.status == 401 )
      {
        this.helper.showLoginExpired().then(res=>{
          this.auth.logout().subscribe(res=>{
            this.router.navigate(["login"]);
          }, err=>{
            this.router.navigate(["login"]);
          });
        });
      }else{     
        this.helper.showErrorMessage();
      }
    });

  }

  ngOnInit() {

   this.lineSvc.getTags().subscribe((res:any)=>{
        console.log(res.data);
        this.tags = res.data;
   });

   this.printerSvc.getAllPrinters().subscribe((res:any)=>{
     this.printers = res.data;
   });

  this.issue.printer =  localStorage.getItem("_selectedPrinter") ? localStorage.getItem("_selectedPrinter")  : "";

  }

  ngAfterViewInit(){
   // jQuery(".form-check label,.form-radio label").append('<i class="input-helper"></i>');
  }

  saveIssue(issueForm){

    var position =1;
    var selectedLine = this.sline.map((obj, i)=>{
      console.log(obj);
     // return obj? this.lines[i-1]:null;
      if(obj)
      {
        
        return {line_id:i, position:position++} 
      }
    });

    if(selectedLine.length==0){
      return alert("Please choose at least one line!");
    }

    if(issueForm.valid){
      let issue = issueForm.value;
      let issueObj = {company_id: this.loggedInCompany.id, name:issue.name, printer: issue.printer, phone:issue.phone, third_party_code:issue.third_party_code, queue_lines:selectedLine}
      this.queueSvc.saveIssue(issueObj).subscribe((res:any)=>{
        this.helper.showSuccessMessage('Save issue Successfully!');
        this.arrivalTime = res.data.created_at;
        this.issueNo = res.data.queue_code;
        this.companyName = res.data.company.name;
        this.note = res.data.company.note;
        this.issueDate = res.data.created_at;

        this.helper.showSuccessMessage("Successfully created queue!");
        let printer = this.issue.printer;
        this.issue = {printer:printer};
        
        this.sline = [];

        this.getLines();
      
     //   this.print();


      }, (err)=>{
        console.log(err);
        this.loading = false;
        this.helper.showErrorMessage();
      });
  
      localStorage.setItem("_selectedPrinter", issue.printer);

    }else{
      alert(" Please provide your information!");
    }



  }

  print() {
    const printContent = document.getElementById("PrintLayoutComponent");
    const WindowPrt = window.open('', '', 'left=0,top=0,width=900,height=900,toolbar=0,scrollbars=0,status=0');
    let printLayout = "<div style='border:1px solid black; text-align:center;width:300px;margin:auto'>\
    <p>Welcome to "+this.companyName+"</p>\
    <p>Arrival:"+this.issueDate+"</p>\
    <h2>"+this.issueNo+"</h2>\
    <p>"+this.note+"</p>\
    </div>";
    WindowPrt.document.write(printLayout);
    WindowPrt.document.close();
    WindowPrt.focus();
    WindowPrt.print();
    WindowPrt.close();
    WindowPrt.onunload = function()
    {
      window.location = environment.urlPrefix+'/dashboard/issue_q';
    }
  }
}

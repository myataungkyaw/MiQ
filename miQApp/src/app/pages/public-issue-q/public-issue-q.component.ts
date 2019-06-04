import { Component, OnInit } from '@angular/core';
import { QueueService } from 'src/app/services/queue.service';
import { HelperService } from 'src/app/services/helper.service';
import { AuthService } from 'src/app/services/auth.service';
import { LineService } from 'src/app/services/line.service';
import { Router } from '@angular/router';
import { environment } from 'src/environments/environment.prod';
import { PrinterService } from 'src/app/services/printer.service';
declare var jQuery;
declare var window:any;

@Component({
  selector: 'app-issue-q',
  templateUrl: './public-issue-q.component.html',
  styleUrls: ['./public-issue-q.component.css']
})
export class PublicIssueQComponent implements OnInit {
  public issue:any = {};
  public loading:boolean = false;
  public loggedInCompany:any={};
  public lines:any = [];
  public sline:any = [];
  public issueNo:string;
  public issueDate:any;
  public companyName:string;
  public arrivalTime:string;
  public note:string;
  public tags:any = [];
  public tagged:any={};
  public printers:any = [];
  public sprinter:any;

  constructor(
    private queueSvc:QueueService, 
    private helper:HelperService, 
    private auth:AuthService, 
    private lineSvc:LineService,
    private printerSvc: PrinterService,
    private router:Router
    ) { 

  this.loggedInCompany = this.auth.getLoginCompany();
  this.companyName = this.loggedInCompany.name;
  let selectedPrinter = localStorage.getItem("_selectedPrinterPublic") ? JSON.parse(localStorage.getItem("_selectedPrinterPublic")) : false;
  this.sprinter = (selectedPrinter) ? selectedPrinter.id : false;

  }

   openFullscreen() {
    var elem:any = document.getElementsByTagName("body")[0];
    if (elem.requestFullscreen) {
      elem.requestFullscreen();
    } else if (elem.mozRequestFullScreen) { /* Firefox */
      elem.mozRequestFullScreen();
    } else if (elem.webkitRequestFullscreen) { /* Chrome, Safari and Opera */
      elem.webkitRequestFullscreen();
    } else if (elem.msRequestFullscreen) { /* IE/Edge */
      elem.msRequestFullscreen();
    }
  }

  getCompanyLines(){
    this.lineSvc.getLineByCompany(this.loggedInCompany.id).subscribe((res:any)=>{
    //  this.lines = res.data;
    console.log(res.data);
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
      //console.log(res.data);
      this.tags = res.data;
 });

  this.printerSvc.getAllPrinters().subscribe((res:any)=>{
    this.printers = res.data;
  });

    // this.auth.fetchCompanies().subscribe(
    //   (res:any)=>{
    //   if(res.data[0]){
    //     this.loggedInCompany = res.data[0];
       this.getCompanyLines();
    //     this.companyName =res.data[0].name;
    //   }
    //   },
    //   (err:any)=>{
    //     console.log(err);
    //   }
    // );
   
  }

  ngAfterViewInit(){
   // jQuery(".form-check label,.form-radio label").append('<i class="input-helper"></i>');

      if(!this.sprinter){
        jQuery('#chooseLine').modal('show');
      }
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
    if( issueForm.valid){
      let issue = issueForm.value;
      let issueObj = {company_id:1, name:issue.name, phone:issue.phone, third_party_code:issue.third_party_code, queue_lines:selectedLine}
      this.queueSvc.saveIssue(issueObj).subscribe((res:any)=>{
        this.helper.showSuccessMessage('Save issue Successfully!');
        this.arrivalTime = res.data.created_at;
        this.issueNo = res.data.queue_code;
        this.issueDate = res.data.created_at;
        this.companyName = res.data.company.name;
        this.note = res.data.company.note;
        this.print();
      }, (err)=>{
        console.log(err);
        this.loading = false;
        this.helper.showErrorMessage();
      });
    }
  }

  print() {
    const printContent = document.getElementById("PrintLayoutComponent");
    const WindowPrt = window.open('', '', 'left=0,top=0,width=900,height=900,toolbar=0,scrollbars=0,status=0');
    let printLayout = "<div style='border:1px solid black; text-align:center;width:300px;margin:auto'>\
    <p>Welcome to "+this.companyName+"</p>\
    <p>Arrival : "+this.issueDate+"</p>\
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
      window.location = environment.urlPrefix+'/issue_q';
    }
  }

  changePrinter(){
    var self = this;
    var index = this.printers.findIndex(printer => printer.id == this.sprinter);
    let selectedPrinter = this.printers[index];
    localStorage.setItem("_selectedPrinterPublic",  JSON.stringify(selectedPrinter));
  }


}

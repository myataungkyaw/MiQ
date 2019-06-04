import { Component, OnInit } from "@angular/core";
import { AuthService } from "src/app/services/auth.service";
import { LineService } from "src/app/services/line.service";
import { HelperService } from "src/app/services/helper.service";
import { Router } from "@angular/router";
import { QueueService } from "src/app/services/queue.service";
import { CommaExpr } from "@angular/compiler";
import { QueueLineService } from 'src/app/services/queue-line.service';
import Echo from 'laravel-echo';
var Pusher = require('pusher-js');
import { environment } from 'src/environments/environment';

var EchoCon:any = new Echo({
  broadcaster: 'pusher',
  key: environment.MIX_PUSHER_APP_KEY,
  cluster: environment.MIX_PUSHER_APP_CLUSTER,
  //encrypted: true,
  wsHost: environment.socketUrl,
  wsPort: environment.socketPort
});

declare var jQuery: any;
@Component({
  selector: "app-call-q",
  templateUrl: "./call-q.component.html",
  styleUrls: ["../../app.component.css", "./call-q.component.css"]
})
export class CallQComponent implements OnInit {
  public showCustomerInfo: boolean = false;
  public loggedInCompany: any;
  public lines: any = [];
  public line: any = {};
  public lineDesks: any = [];
  public counter: any = {};
  public loading: boolean = false;
  public searchName: string;
  public queueLines: any = [];
  public nextQueue: any = {queue:{}};
  public hasNext: boolean = false;
  public currentQueue: any = {queue:{}};
  public hasCurrent: boolean = false;
  public nextLine: string;
  public nextLineId: number;
  public currentLine: string;
  public currentDesk:any;
  public currentLineId: number;
  public nextIndex: number;
  public selectQueueLineId: number;
  public selectedLine: Number = null;
  public selectedQLine: Number = null;
  public selectedLineObj: any;
  public selectedQueueLineIndex:number;
  public processingCustomer:any={};
  public disabledCall: boolean = false;

  constructor(
    private auth: AuthService,
    private lineSvc: LineService,
    public helper: HelperService,
    private router: Router,
    private queueSvc: QueueService,
    private queuelineSvc: QueueLineService
  ) {

    //console.log(this.helper.generateQueueNumber('1'));

    this.loggedInCompany = this.auth.getLoginCompany();
    this.lineSvc.getLineByCompany(this.loggedInCompany.id).subscribe(
      (res: any) => {
        this.lines = res.data.lines;
      },
      err => {
        this.loading = false;
        if (err.status == 401) {
          this.helper.showLoginExpired().then(res => {
            this.auth.logout().subscribe(
              res => {
                this.router.navigate(["login"]);
              },
              err => {
                this.router.navigate(["login"]);
              }
            );
          });
        } else {
          this.helper.showErrorMessage();
        }
      }
    );
  }

  ngOnInit() {



  }

  openLineModal() {
    jQuery("#chooseLine").modal("toggle");
  }

  openSelectCounterModal() {
    jQuery("#chooseDesk").modal("toggle");
  }

  openWaitingListModal() {
    jQuery("#searchDialog").modal("toggle");
    this.getQueueLine();
  }



  ngAfterViewInit() {
    jQuery(".form-check label,.form-radio label").append(
      '<i class="input-helper"></i>'
    );
  }

  changeLine(line) {
    if(this.hasCurrent){
       alert("Please finish current queue first!");
       return false;
   }

    this.line = line;
    this.nextLine = line.name;
    this.nextLineId = line.id;
    this.lineDesks = line.line_desks;
    jQuery("#chooseLine").modal("toggle");

    this.getQueueLine();
   

    //clear next queue
    this.nextQueue = {queue:{}};
    this.hasNext = false;

    EchoCon.channel('issue_q_'+line.id)
    .listen('IssueNewQueue', (e:any)=>{
      console.log(e);
    // alert("Knock knock!There is a new queue for the line!");
     this.getQueueLine();
    // this.zone.run(()=>{});

    //  alert("Ting Tong Ting Tong!");
    });
    
  }

  // getQueue() {
  //   let company = this.auth.getLoginCompany();
  //   this.queueSvc
  //     .getQueue({ company_id: company.id, name: this.searchName, line_id: this.line.id })
  //     .subscribe((res: any) => {
  //       this.queues = res.data;
  //     });
  // }

  getQueueLine() {
    
    let company = this.auth.getLoginCompany();

    this.queuelineSvc
      .getQueueLine({ name: this.searchName, line_id: this.line.id })
      .subscribe((res: any) => {
        this.queueLines = res.data;
        console.log(this.queueLines);
      });
  }

  getCurrentQueueLine() {
    
    let company = this.auth.getLoginCompany();
    this.queuelineSvc
      .getCurrentQueueLine({ name: this.searchName, line_id: this.line.id , desk_id: this.counter.id })
      .subscribe((res: any) => {
        console.log(res.data);
        if(res.data[0]){
          this.currentQueue = res.data[0];
          this.hasCurrent = true;
        }else{
          this.currentQueue =  {queue:{}};
          this.hasCurrent = false;
        }
      });
  }

  changeQueue(queue, i) {
    this.nextQueue = queue;
    this.hasNext = true;
    this.nextIndex = i;
  
    jQuery("#searchDialog").modal("toggle");
  }

  changeCounter(desk) {
    this.counter = desk;
    console.log(this.counter);
    jQuery("#chooseDesk").modal("toggle");
    
    this.getCurrentQueueLine();

   // console.log(this.queueLines);
    //get first available person for next
    if(this.queueLines.length>0){

        let qq = this.queueLines[0];
        if(qq.on_hold==1){ 
         // alert("next item is on hold!");
          if(this.queueLines[1]){

            if(this.queueLines[1].on_hold != 1){ //this one also should not be on hold
              this.hasNext = true;
              this.nextIndex = 1;
              this.nextQueue = this.queueLines[1];
            }
           
          }
        }else{
          this.hasNext = true;
          this.nextQueue = this.queueLines[0];
          this.nextIndex = 0;
        }
       
    }

    //check if in progress queue for current 

  
  }

  selectQueueLine(qLine, i) {
    // console.log(qLine);
    this.selectQueueLineId = qLine.id;
    this.selectedQueueLineIndex = i;
  }

  addSelectedQueueClass(id) {
    if (this.selectQueueLineId == id) return true;
    else return false;
  }

  selectLine(Line) {
  
    this.selectedLine = Line.id;
    this.selectedLineObj = Line;
  }

  addSelectedLineClass(id) {
    if (this.selectedLine == id) return true;
    else return false;
  }

  callQ(queueLine) {
   // alert("Ting Tong Ting Tong!");
   this.disabledCall = true;
    queueLine.call_number = queueLine.call_number+1;
    let obj:any = {id:queueLine.id, call_number:queueLine.call_number, desk_id: this.counter.id  };
    this.queuelineSvc.updateQueueLine(obj).subscribe(res=>{
      console.log(res);
    });

    setTimeout(()=>{
      this.disabledCall = false;
    }, 5000);
  }

  addLine() {

    //check if the line is already there 
   // console.log(this.selectedLineObj);
   // console.log(this.processingCustomer.queue_lines);
    const found = this.processingCustomer.queue_lines.findIndex(line => line.line_id == this.selectedLineObj.id);

    if(found>-1){
      return alert("This Line is already in the list!");
    }

    let queueLength = this.processingCustomer.queue_lines.length - 1;
    let lastPosition = 0;//no need

    this.queueSvc
      .addQueueLine(this.selectedLineObj.id, this.processingCustomer.id, lastPosition)
      .subscribe((res: any) => {
        this.helper.showSuccessMessage("Add Line Successfully");
        let queueLine: any = res.data;
        // queueLine.line_id = this.selectedLineObj.id;
        // queueLine.name = this.selectedLineObj.name;
        // queueLine = { id:res.data.id, position: res.data.position };

        this.processingCustomer.queue_lines.push(queueLine);
        this.selectedLine = 0;
      });

  }

  upLine() {
    let previousIndex = this.selectedQueueLineIndex-1;
    if (previousIndex >= 0)
    {
      this.moveArrayIndex(this.processingCustomer.queue_lines, this.selectedQueueLineIndex, previousIndex);
      console.log(this.processingCustomer.queue_lines);
      this.queueSvc.updateQueuePosition(this.processingCustomer.queue_lines).subscribe((res:any)=>{
        this.helper.showSuccessMessage("Order Line Successfully");
      });

    }
     
  }

  downLine() {
    let nextIndex = this.selectedQueueLineIndex+1;
    if (nextIndex < this.processingCustomer.queue_lines.length){
      this.moveArrayIndex(this.processingCustomer.queue_lines, this.selectedQueueLineIndex, nextIndex);
      this.queueSvc.updateQueuePosition(this.processingCustomer.queue_lines).subscribe((res:any)=>{
        this.helper.showSuccessMessage("Order Line Successfully");
      });
    }
  }

  removeLine() {
    let removeQueueLine = this.processingCustomer.queue_lines.splice(this.selectedQueueLineIndex, 1);
    this.queueSvc.cancelQueue(removeQueueLine).subscribe((res:any)=>{
      this.helper.showSuccessMessage("Remove Line Successfully");
    });
  }

  noShow(){
    //remove item from array 
    const foundIndex = this.queueLines.findIndex(ql => ql.id == this.nextQueue.id);
    this.queueLines.splice(foundIndex, 1);
    this.queueSvc
      .noShowQueue(this.nextQueue.id)
      .subscribe((res: any) => {
        this.helper.showSuccessMessage("Remove Queue Successfully");

        if(this.queueLines[0]) {

          if(this.queueLines[0].on_hold != 1){
            this.nextIndex = 0;
            this.nextQueue = this.queueLines[this.nextIndex];
          }

      //    this.startServing();
        }else{
          this.hasNext = false;
          this.nextQueue = {queue:{}};
          this.helper.showSuccessMessage("No Queue left!");
        }
      });
  }

  startServing() {
    if(this.hasCurrent){
      return alert("Please finish current queue first!");
    }

    this.hasCurrent = true;
    this.currentQueue = this.nextQueue;
    //what are these ??
    this.currentLine = this.nextLine;
    this.currentLineId = this.nextLineId;

    this.queueSvc.serveQueue(this.currentQueue.id, this.counter.id).subscribe((res:any)=>{

    });

         //remove from the list 
         const foundIndex = this.queueLines.findIndex(ql => ql.id == this.currentQueue.id);
         this.queueLines.splice(foundIndex, 1);

    if (this.queueLines[0]) {
      let qq = this.queueLines[0];
      if(qq.on_hold==1){  //check if next item is on hold
   //     alert("next item is on hold!");
        if(this.queueLines[1]){
          this.nextIndex = 1;
          this.nextQueue = this.queueLines[1];
        }
      }else{
        this.nextIndex = 0;
        this.nextQueue = this.queueLines[0];
      }
     

    }else{
    //  alert('no more queue');
      this.helper.showSuccessMessage("No Queue left!");
      this.nextQueue = {queue:{}};//empty
      this.hasNext = false;
    }

  }


insertIntoArr(arr, index, item ) {
    arr.splice( index, 0, item );
    return arr;
}

  goToQ(queue_line_id){
  // alert(queue_line_id);
  this.queuelineSvc.returnQueueLine(queue_line_id)
  .subscribe((res)=>{
  this.getQueueLine();
  },
  (err)=>{
    console.log(err);
    alert("Cannot return to Q!");
  });
    //logic for return q 
     let queueLineIndex = this.queueLines.findIndex(line => line.id == queue_line_id);
    // console.log(queueLineIndex);
  
    if (this.queueLines[queueLineIndex+1]) {
      let prevNextQ = this.queueLines[queueLineIndex];

      if(this.queueLines[queueLineIndex+1].on_hold != 1){  //check if next item is on hold

        this.nextIndex = queueLineIndex;
        this.nextQueue = this.queueLines[queueLineIndex+1];

      }
     

      //update the array
     // this.queueLines[this.nextIndex] = prevNextQ;
    //  this.queueLines[this.nextIndex+1] = this.nextQueue;
   //   this.insertIntoArr(this.queueLines, queueLineIndex+1, prevNextQ);

    }else{
    //  alert('no more queue');
      this.helper.showSuccessMessage("No Queue left!");
    }

  }

  openTransferModal() {
   
    this.queueSvc.getOneQueue(this.currentQueue.queue.id).subscribe(
      (res:any)=>{
        if(res.data){
          this.processingCustomer = res.data;
         // this.currentQueue.queue.queue_lines = res.data.queue_lines;
        }
      },
      (err:any)=>{
        console.log(err);
      });

      jQuery("#transferDialog").modal("toggle");
  }

  finishedQueue() {
    this.queuelineSvc
      .finishQueueLine(this.currentQueue.id)
      .subscribe((res: any) => {
        this.helper.showSuccessMessage("Finished Queue Successfully");
        this.currentQueue = {queue:{}};
        this.hasCurrent = false;
      });

      this.getQueueLine();

  }

  moveArrayIndex(arr, old_index, new_index) {
    while (old_index < 0) {
        old_index += arr.length;
    }
    while (new_index < 0) {
        new_index += arr.length;
    }
    if (new_index >= arr.length) {
        var k = new_index - arr.length;
        while ((k--) + 1) {
            arr.push(undefined);
        }
    }
    arr.splice(new_index, 0, arr.splice(old_index, 1)[0]);  
   return arr;
  }

}

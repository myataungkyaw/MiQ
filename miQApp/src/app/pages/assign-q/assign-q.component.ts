import { Component, OnInit } from '@angular/core';
import { LineService } from 'src/app/services/line.service';
import { AuthService } from 'src/app/services/auth.service';
import { QueueService } from 'src/app/services/queue.service';
import { QueueLineService } from 'src/app/services/queue-line.service';
import { HelperService } from 'src/app/services/helper.service';
declare var jQuery: any;

@Component({
  selector: 'app-assign-q',
  templateUrl: './assign-q.component.html',
  styleUrls: ['../../app.component.css', './assign-q.component.css']
})
export class AssignQComponent implements OnInit {
  public showCustomerInfo:boolean = false;
  public keyword:any;
  public lines:any = [];
  public loggedInCompany:any;
  public customers:any = [];
  public currentQueue:any = {queue:{}};
  public processingCustomer:any={};
  public selectQueueLineId: number;
  public selectedLine: Number = null;
  public selectedQLine: Number = null;
  public selectedLineObj: any;
  public selectedQueueLineIndex:number;

  constructor(private lineSvc: LineService,
    private queueSvc: QueueService,
    private queuelineSvc: QueueLineService,
    public helper: HelperService,
    private auth: AuthService) { 
     this.loggedInCompany = this.auth.getLoginCompany();
  }

  ngOnInit() {
    this.lineSvc.getLineByCompany(this.loggedInCompany.id).subscribe(
    (res:any)=>{
      console.log(res);
      this.lines = res.data.lines;
    },
    (err:any)=>{
      console.log(err);
    });
  }

  searchCustomers(){
  this.showCustomerInfo=true;
  
  this.queueSvc.getQueue({name: this.keyword}).subscribe(
    (res:any)=>{
      console.log(res);
      if(res.data){
        this.customers = res.data;
      }
    },
    (err:any)=>{
      console.log(err);
    }
  );

  }

  onSelectCustomer(customerQ){
    //getQueueLine by Q
    this.currentQueue ={queue:customerQ};
    this.queueSvc.getOneQueue(this.currentQueue.queue.id).subscribe(
      (res:any)=>{
        if(res.data){
          this.processingCustomer = res.data;
         // this.currentQueue.queue.queue_lines = res.data.queue_lines;
         jQuery("#transferDialog").modal("toggle");

        }
      },
      (err:any)=>{
        console.log(err);
      });

  
  }

    selectQueueLine(qLine, i) {
    console.log(qLine);
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
    queueLine.call_number = queueLine.call_number+1;
    let obj:any = {id:queueLine.id, call_number:queueLine.call_number };
    this.queuelineSvc.updateQueueLine(obj).subscribe(res=>{
      console.log(res);
    });
  }

  addLine() {

    //check if the line is already there 
    console.log(this.selectedLineObj);
    console.log(this.processingCustomer.queue_lines);
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

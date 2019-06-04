import { Component, OnInit } from '@angular/core';
import { QueueService } from 'src/app/services/queue.service';
import { AuthService } from 'src/app/services/auth.service';

@Component({
  selector: 'app-public-check-q',
  templateUrl: './public-check-q.component.html',
  styleUrls: ['./public-check-q.component.css']
})
export class PublicCheckQComponent implements OnInit {

  showCustomerInfo = true;
  public searchName:string;
  public queue:any = [];
  public queueLines:any = [];
  public all_queues:any = [];
  constructor(private queueSvc:QueueService, private auth:AuthService) { 
    
  }

  ngOnInit() {
    let company = this.auth.getLoginCompany();
    this.queueSvc.getQueue({company_id:company.id, 'name':this.searchName}).subscribe((res:any)=>{
      if (res.data.length > 0 )
        this.all_queues = res.data;
    });
  }

  getQueue(){
    let company = this.auth.getLoginCompany();
    this.queueSvc.getQueue({company_id:company.id, 'name':this.searchName}).subscribe((res:any)=>{
      this.queue = res.data;
      if (this.queue.length > 0 ){
        this.queueLines = this.queue[0].queue_lines;
      }else{
        this.queueLines = [];
        alert("Not found!");
      }
       
    });
  }

  onChooseQ(e){
    this.getQueue();
  }
}

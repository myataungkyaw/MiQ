import { Component, OnInit, NgZone } from '@angular/core';
import { LineService } from 'src/app/services/line.service';
import Echo from 'laravel-echo';
import { environment } from 'src/environments/environment';
import { AuthService } from 'src/app/services/auth.service';
var Pusher = require('pusher-js');

var EchoCon:any = new Echo({
  broadcaster: 'pusher',
  key: environment.MIX_PUSHER_APP_KEY,
  cluster: environment.MIX_PUSHER_APP_CLUSTER,
  //encrypted: true,
  wsHost: environment.socketUrl,
  wsPort: environment.socketPort
});

declare var jQuery:any;

@Component({
  selector: 'app-room-screen',
  templateUrl: './room-screen.component.html',
  styleUrls: ['./room-screen.component.css']
})
export class RoomScreenComponent implements OnInit {
  public company_id: any;
  public line_id: any;
  public lines:any = [];
  public desks:any = [];
  public sline:any;
  public selectedLine:any = {};
  public calling:any = null;
  public company:any = {};
  constructor(public lineSvc: LineService,
    private zone:NgZone,
    public authSvc: AuthService) { 
    this.company = this.authSvc.getLoginCompany();
  }

  ngOnInit() {

    if(!this.sline){
      jQuery('#chooseLine').modal('show');
    }

    this.lineSvc.getLineByCompany(this.company.id).subscribe(
      (res: any) => {
        this.lines = res.data.lines;
      },
      (err) => {
        console.log(err);
      });


     this.lineSvc.getDesksByCompany(1).subscribe((res:any)=>{
      console.log(res.data);
      this.desks = res.data;
      //jQuery(".form-check label,.form-radio label").append('<i class="input-helper"></i>');
    });
  }

  changeLine(){
    var self = this;
    var index = this.desks.findIndex(line => line.id == this.sline);
    this.selectedLine = this.desks[index];
   // console.log(this.selectedLine);

   EchoCon.channel('call_q_'+this.sline)
   .listen('CallQueue', (e:any)=>{
 
     console.log(e);
     self.calling = e.queue;
    

     self.zone.run(()=>{

     });
    
   //  alert("Ting Tong Ting Tong!");
   });


  }

}

import { Component, OnInit, NgZone } from '@angular/core';
import Echo from 'laravel-echo';
import { environment } from 'src/environments/environment';
import { LineService } from 'src/app/services/line.service';
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


var snd;
var company = localStorage.getItem("_loggedInCompany") ? JSON.parse(localStorage.getItem("_loggedInCompany")) : {};
if(company.notification_sound){
  snd = new Audio(company.notification_sound);
  snd.preload = "auto";
}else{
  snd = new Audio("http://"+window.location.host+environment.pathPrefix+"/assets/audio/slow-spring-board.mp3");
  snd.preload = "auto";
}

declare var jQuery:any;

@Component({
  selector: 'app-display-screen',
  templateUrl: './display-screen.component.html',
  styleUrls: ['./display-screen.component.css']
})
export class DisplayScreenComponent implements OnInit {
  public lines:any = [];
  public desks:any = [];
  public slines:any = [];
  public selectedLines: any =  [];
  public misseds:any = [];
  public no_shows:any = [];
  public currentCalling:any = {line:{name:''},queue:{queue_number:''}};
  public company:any = {};
  constructor(private zone:NgZone,
  private lineSvc: LineService,
  public authSvc: AuthService) { 
      this.company = this.authSvc.getLoginCompany();
      this.selectedLines =  localStorage.getItem("_selectedLines") ? JSON.parse(localStorage.getItem("_selectedLines")) : [];
     
     
  }

  ngOnInit() {
    var self = this;

  

    if(this.selectedLines.length==0){
      jQuery('#chooseLine').modal('show');
    }else{

      snd.addEventListener('canplaythrough', ()=> { 
        this.listenLines(this.selectedLines);
     }, false);
    }

    this.lineSvc.getLineByCompany(this.company.id).subscribe((res:any)=>{
      this.lines = res.data.lines;
      //jQuery(".form-check label,.form-radio label").append('<i class="input-helper"></i>');
    });

    this.lineSvc.getDesksByCompany(this.company.id).subscribe((res:any)=>{
      console.log(res.data);
      this.desks = res.data;
      //jQuery(".form-check label,.form-radio label").append('<i class="input-helper"></i>');
    });


// EchoCon.channel('call_q')
// .listen('CallQueue', (e:any)=>{
//   console.log(e);

//   self.currentCalling = e.queue;
//   snd.play();
//   console.log(self.currentCalling);
//   self.zone.run(()=>{});
// //  alert("Ting Tong Ting Tong!");
// });

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

  chooseLines(){
    var self = this;
    var seLines = this.slines.map((obj, i)=>{
      if(obj){ 
        var index = this.desks.findIndex(line => line.id == i);
        return this.desks[index]; 
      }
    });
    seLines = seLines.filter((el)=>{ return el != null; });
    this.selectedLines = seLines;

    localStorage.setItem("_selectedLines", JSON.stringify(seLines));
    // console.log(seLines);
  this.listenLines(seLines);

  }

  listenLines(seLines){
    var self = this;

    

    for(var i = 0; i< seLines.length; i++){

      EchoCon.channel('call_q_'+seLines[i].id)
      .listen('CallQueue', (e:any)=>{
    
        console.log(e);
        self.currentCalling = e.queue;
       snd.play();
        var index = self.selectedLines.findIndex(line => line.id == e.queue.line_desk_id);
        // alert(index);
        self.selectedLines[index].call_number = self.currentCalling.queue.queue_number;
        
       //  jQuery(".grid_"+i).addClass("blink_me");

        self.zone.run(()=>{

        });
       
      //  alert("Ting Tong Ting Tong!");
      });

      EchoCon.channel('call_q_no_show_'+seLines[i].id)
      .listen('NoShowQueue', (e:any)=>{
      
        console.log(e);
        self.no_shows.push(e.queue);
        
        self.zone.run(()=>{

        });
       
      });



    }

  }

  ngAfterViewInit(){
    if(this.company.background_image){
      jQuery("body").css("background-image", "url("+this.company.background_image+")");
      jQuery("body").css("background-size","cover");
    }
   
  }

  goFullScreen(){
      alert("fullscreen")
      var root:any = document.documentElement;
      return root.requestFullscreen || root.webkitRequestFullscreen || root.mozRequestFullScreen || root.msRequestFullscreen
  
  }

}

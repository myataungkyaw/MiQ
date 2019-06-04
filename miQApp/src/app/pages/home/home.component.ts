import { Component, OnInit } from '@angular/core';
import { AuthService } from 'src/app/services/auth.service';
declare var $:any;
@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.css']
})
export class HomeComponent implements OnInit {
  public loggedInUser:any;
  constructor(private auth:AuthService) { 
    this.loggedInUser = this.auth.getLoggedInUser();
  }

  ngOnInit() {
  }

  ngAfterViewInit(){
    // var w = $(".btn-square").width();
    // console.log(w);
    // $(".btn-nav").width(w+"px");
    // $(".btn-nav").height(w+"px");
    // $(window).on("resize", function(){
    //   var w = $(".btn-square").width();
    //   console.log(w);
    //   $(".btn-nav").width(w+"px");
    //   $(".btn-nav").height(w+"px");
    // });
  }
}

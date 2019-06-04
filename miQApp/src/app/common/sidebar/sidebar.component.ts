import { Component, OnInit } from '@angular/core';
import { AuthService } from 'src/app/services/auth.service';
import { environment } from 'src/environments/environment';

@Component({
  selector: 'app-sidebar',
  templateUrl: './sidebar.component.html',
  styleUrls: ['./sidebar.component.css']
})
export class SidebarComponent implements OnInit {
  //public loggedL
     public loggedInUser:any;
  constructor(public auth: AuthService,
    ) { 
      this.loggedInUser = this.auth.getLoggedInUser();
    }

  ngOnInit() {

  }

  logout(){
    var yes = confirm("Are you sure you want to logout ?");
    if(!yes){return;}
    this.auth.logout().subscribe((res:any)=>{
      console.log("Done logging out");
    });
   window.location.href = environment.urlPrefix+"/login";
  }

}

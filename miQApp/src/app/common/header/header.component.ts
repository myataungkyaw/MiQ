import { Component, OnInit } from '@angular/core';
import { AuthService } from 'src/app/services/auth.service';

@Component({
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.css']
})
export class HeaderComponent implements OnInit {
 public company:any = {};
  constructor(public authSvc: AuthService) { 
  
  }

  ngOnInit() {
    this.company = this.authSvc.getLoginCompany();
  }

}

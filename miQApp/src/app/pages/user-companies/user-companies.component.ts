import { Component, OnInit } from '@angular/core';
import { AuthService } from 'src/app/services/auth.service';
import { Router } from '@angular/router';
import { environment } from 'src/environments/environment';
declare var window:any;
@Component({
  selector: 'app-user-companies',
  templateUrl: './user-companies.component.html',
  styleUrls: ['./user-companies.component.css']
})
export class UserCompaniesComponent implements OnInit {
  public userCompanies:any = [];
  constructor(private auth:AuthService, private router:Router) { 
    this.userCompanies = this.auth.getUserCompanies();
  }

  ngOnInit() {
  }

  chooseCompany(company){
    this.auth.setLoginCompany(company);
    //this.router.navigate(['/dashboard']);
    window.location = environment.urlPrefix+'/dashboard';
  }


}

import { Component, OnInit } from '@angular/core';
import { AuthService } from "../../services/auth.service";
import { HelperService } from "../../services/helper.service";
import { Router } from '@angular/router';
import { environment } from '../../../environments/environment';

declare var window:any;
@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent implements OnInit {
  public loading:Boolean = false;
  public user:any = {};
  public env:any;
  constructor(private authSvc:AuthService, 
    private helper:HelperService, 
    private router:Router) { 
      
    this.env = environment;
  }
  ngOnInit() {
  }

  login(loginForm){
    if (loginForm.valid)
    {
      this.loading = true;
      let user = loginForm.value;
      let userObj = {email:user.email, password:user.password};
      
      this.authSvc.login(userObj).subscribe((res:any)=>{
        this.loading = false;
        if(res.access_token){
        //  localStorage.setItem("_access_token", res.access_token);
          this.authSvc.setLoggedInUser(res.user);
          this.authSvc.setToken(res.access_token);
          this.helper.showSuccessMessage('LoggedIn Successfully');
          this.authSvc.setUserCompanies(res.user_companies);
          if(res.user_companies.length>1){
            window.location = environment.urlPrefix+'/dashboard/user_companies';
          }else{
            this.authSvc.setLoginCompany(res.user_companies[0]);
            window.location = environment.urlPrefix+'/dashboard';
          }
         
        }else{
          //need to show error 
          alert("Username and Password do not match!");
        }
     
      }, (res:any)=>{
        this.loading = false;
        //need to show error
        if(res.error.error){
          this.helper.showErrorMessage();
        } 
        //will remove later
          //this.router.navigate(["/dashboard/user_companies"]);
         // window.location = environment.urlPrefix+'/dashboard/user_companies';

      });

    }
  }

}

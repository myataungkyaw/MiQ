import { Component, OnInit } from '@angular/core';
import { UserService } from "../../services/user.service";
import { HelperService } from "../../services/helper.service";
import { AuthService } from 'src/app/services/auth.service';
import { Router } from '@angular/router';
declare var jQuery;
@Component({
  selector: 'app-users',
  templateUrl: './users.component.html',
  styleUrls: ['../../app.component.css', './users.component.css']
})
export class UsersComponent implements OnInit {
  public users:any = [];
  public loading:any = true;
  public query:any = {};
  public changeUser:any = {};
  public updateId:any = 0;
  constructor(private userSvc:UserService, private helper:HelperService, private auth:AuthService, private router:Router) { }

  ngOnInit() {
    console.log('nginit');
    this.userSvc.getAllUsers().subscribe((res:any)=>{
      console.log(res);
      this.users = res.data;
      this.loading = false;
    }, (err)=>{
      this.loading = false;
      if ( err.status == 401 )
      {
        this.helper.showLoginExpired().then(res=>{
          this.auth.logout().subscribe(res=>{
            this.router.navigate(["login"]);
          }, err=>{
            this.router.navigate(["login"]);
          });
        });
      }else{     
        this.helper.showErrorMessage();
      }
    });
  }

  onSearchFormSubmit(f){
    this.loading = true;
    this.users = [];
    this.userSvc.getUsers(this.query).subscribe(
      (res)=>{
          let result:any = res;
          if(result.success){
            this.users = result.data;
          }
          this.loading = false;
      },
      (err)=>{
        this.loading = false;
        if ( err.status == 401 )
        {
          this.helper.showLoginExpired().then(res=>{
            this.auth.logout().subscribe(res=>{
              this.router.navigate(["login"]);
            }, err=>{
              this.router.navigate(["login"]);
            });
          });
        }else{     
          this.helper.showErrorMessage();
        }
      }
    );
  }

  changePasswordDialog(id){
    this.updateId = id;
    jQuery('#changePassword').modal('toggle');
    jQuery('#changePasswordForm').trigger("reset");

  }

  changePassword(formData){
    if(!formData.valid){
      return;
    }
    this.loading = true;
    this.userSvc.changePassword(this.changeUser, this.updateId).subscribe(
      (res)=>{
          let result:any = res;
          this.loading = false;
          jQuery('#changePassword').modal('toggle');
          this.helper.showSuccessMessage('Change Password Successfully!');
      },
      (err)=>{
        this.loading = false;
        if ( err.status == 401 )
        {
          this.helper.showLoginExpired().then(res=>{
            this.auth.logout().subscribe(res=>{
              this.router.navigate(["login"]);
            }, err=>{
              this.router.navigate(["login"]);
            });
          });
        }else{     
          this.helper.showErrorMessage();
        }
      }
    );
  }

}

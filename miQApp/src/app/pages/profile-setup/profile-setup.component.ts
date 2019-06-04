import { Component, OnInit } from '@angular/core';
import { AuthService } from 'src/app/services/auth.service';
import { UserService } from 'src/app/services/user.service';
import { HelperService } from 'src/app/services/helper.service';

@Component({
  selector: 'app-profile-setup',
  templateUrl: './profile-setup.component.html',
  styleUrls: ['./profile-setup.component.css']
})
export class ProfileSetupComponent implements OnInit {
 public user:any;
  constructor(public auth: AuthService , 
    public userSvc: UserService , 
    public helper: HelperService) { 
    this.user = this.auth.getLoggedInUser();
  }

  ngOnInit() {
  }

  saveProfile(form){
  
    if(!form.valid){
      this.helper.showSuccessMessage("Validation error!");
      return;
    }
    if(this.user.new_password && this.user.new_password != this.user.confirm_password){
      alert("Passwords do not match!");
      return;
    }
    
    this.userSvc.updateUser(this.user, this.user.id).subscribe(
      (res:any)=>{
      this.helper.showSuccessMessage("Successfully updated!");
      },
      (res:any)=>{
        alert("Error saving profile info.");
      }
    );
  }

}

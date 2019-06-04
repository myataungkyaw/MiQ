import { Component, OnInit } from '@angular/core';
import { UserService } from "../../services/user.service";
import { HelperService } from "../../services/helper.service";
import { MacroService } from "../../services/macro.service";
import { Router } from '@angular/router';

@Component({
  selector: 'app-add-user',
  templateUrl: './add-user.component.html',
  styleUrls: ['../../app.component.css','./add-user.component.css']
})
export class AddUserComponent implements OnInit {
  public user:any={};
  public loading:boolean = false;
  public roles:any;
  constructor(private userSvc:UserService, 
    private helper:HelperService,
    private router:Router,
    private macro:MacroService
    ) { }

  ngOnInit() {
    this.macro.getRoles().subscribe((res:any)=>{
      this.roles = res.data;
    });
  }

  saveUser(userForm:any){
    if (userForm.valid)
    {
      let user = userForm.value;
      let userObj = {name:user.name, email:user.email, phone:user.phone, password:user.password, role:user.role}
      this.userSvc.addUser(userObj).subscribe((res:any)=>{
        this.helper.showSuccessMessage('Saved User Successfully');
        this.router.navigate(["dashboard/users"]);
      }, (err)=>{
        this.loading = false;
        this.helper.showErrorMessage();
      });
    }
    
  }

}

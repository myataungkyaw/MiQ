import { Component, OnInit } from '@angular/core';
import { UserService } from "../../services/user.service";
import { HelperService } from "../../services/helper.service";
import { MacroService } from "../../services/macro.service";
import { Router, ActivatedRoute } from '@angular/router';

@Component({
  selector: 'app-edit-user',
  templateUrl: './edit-user.component.html',
  styleUrls: ['./edit-user.component.css']
})
export class EditUserComponent implements OnInit {
  public user:any = {};
  public id:any = 0;
  public loading:boolean = false;
  public roles:any;
  constructor(
    private userSvc:UserService, 
    private helper:HelperService,
    private router:Router,
    private activeRouter:ActivatedRoute,
    private macro:MacroService
    ) { }

  ngOnInit() {
    this.activeRouter.paramMap.subscribe(params => {
      this.id = params.get('id');
   });
    this.getUser(this.id);
    this.macro.getRoles().subscribe((res:any)=>{
      this.roles = res.data;
    });
  }

  getUser(id){
    this.userSvc.getUserById(id).subscribe((res:any)=>{
      this.user = res.data;
    }, (err)=>{
      this.loading = false;
      this.helper.showErrorMessage();
    });
  }

  updateUser(userForm){
    if (userForm.valid){
      let user = userForm.value;
      let userObj = {name:user.name, email:user.email, phone:user.phone, role:user.role}
      this.userSvc.updateUser(userObj, this.id).subscribe((res:any)=>{
        this.helper.showSuccessMessage('Update User Successfully');
        this.router.navigate(["/dashboard/users"]);
      }, (err)=>{
        this.loading = false;
        this.helper.showErrorMessage();
      });
    }
  }

}

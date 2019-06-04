import { Component, OnInit } from '@angular/core';
import { AuditLogService } from "../../services/audit-log.service";
import { UserService } from "../../services/user.service";
import { HelperService } from "../../services/helper.service";

@Component({
  selector: 'app-audit-logs',
  templateUrl: './audit-logs.component.html',
  styleUrls: ['./audit-logs.component.css']
})
export class AuditLogsComponent implements OnInit {
  public audits:any = [];
  public users:any = [];
  public query:any = {
    category:"",
    user_id:""
  };
  public loading:any = true;
  constructor(private auditSvc:AuditLogService, 
    private helper:HelperService, 
    private userSvc:UserService) {
      
     }

  ngOnInit() {
    this.fetchAuditLog();
    this.fetchUser();
  }

  fetchAuditLog(){
    this.auditSvc.getAllAudits().subscribe((res:any)=>{
      console.log(res);
      this.audits = res.data.data;
      this.loading = false;
    },(err)=>{
      console.log(err);
      this.loading = false;
      this.helper.showErrorMessage();
    });
  }

  onSearchFormSubmit(formData){
    this.loading = true;
    this.audits = [];
    this.auditSvc.getAudits(this.query).subscribe(
      (res)=>{
        console.log(res);
          let result:any = res;
          if(result.success){
            this.audits = result.data.data;
          }
          this.loading = false;
      },
      (err)=>{
        console.log(err);
        this.loading = false;
        this.helper.showErrorMessage();
      }
    );
  }

  fetchUser(){
    this.userSvc.getAllUsers().subscribe((res:any)=>{
      console.log(res);
      this.users = res.data;
      this.loading = false;
    },(err)=>{
      console.log(err);
      this.loading = false;
      this.helper.showErrorMessage();
    });
  }

}

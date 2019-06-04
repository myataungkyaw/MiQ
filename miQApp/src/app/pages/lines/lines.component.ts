import { Component, OnInit } from '@angular/core';
import { LineService } from "../../services/line.service";
import { HelperService } from "../../services/helper.service";
import { Router } from '@angular/router';
import { AuthService } from 'src/app/services/auth.service';

@Component({
  selector: 'app-lines',
  templateUrl: './lines.component.html',
  styleUrls: ['./lines.component.css']
})
export class LinesComponent implements OnInit {
  public lines:any = [];
  public loading:any = true;
  public total_desks:any = 0;
  constructor(
    private lineSvc:LineService, 
    private helper:HelperService, 
    public router:Router,
    private auth:AuthService
    ) { }

  ngOnInit() {
    this.lineSvc.getAllLines().subscribe((res:any)=>{
      this.lines = res.data;
      this.lines.map((l,i)=>{
        this.total_desks += l.line_desks.length;
      });
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

}

import { Injectable } from '@angular/core';
import { environment } from '../../environments/environment';
import { prod_environment } from '../../environments/environment.prod';
import { HttpClient } from '@angular/common/http';
import { AuthService } from './auth.service';

@Injectable({
  providedIn: 'root'
})
export class AuditLogService {
  private config: any;
  private httpHeaderOptions: any;
  constructor(private http:HttpClient, private authSvc:AuthService) { 
    this.config = (window.location.host.indexOf('localhost'))? prod_environment:environment;
    this.httpHeaderOptions = this.authSvc.getAuthHeader();
  }

  getAllAudits() {
    let url = this.config.baseUrl + 'dashboard/audit_logs';
    return this.http.get(url, this.httpHeaderOptions);
  }

  getAudits(query=null) {
    let qstr = "?";
    if(query){
        qstr += this.serialize(query);
    }
    let url = this.config.baseUrl + 'dashboard/audit_logs'+qstr;
    return this.http.get(url, this.httpHeaderOptions);
  }

  serialize(obj) {
    var str = [];
    for (var p in obj)
      if (obj.hasOwnProperty(p) && obj[p]) {
        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
      }
    return str.join("&");
  }
}

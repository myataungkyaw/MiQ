import { Injectable } from '@angular/core';
import { environment } from '../../environments/environment';
import { prod_environment } from '../../environments/environment.prod';
import { HttpClient } from '@angular/common/http';
import { AuthService } from './auth.service';

@Injectable({
  providedIn: 'root'
})
export class MacroService {

  private config: any;
  private httpHeaderOptions: any;
  constructor(private http:HttpClient, private authSvc:AuthService) { 
    this.config = (window.location.host.indexOf('localhost'))? prod_environment:environment;
    this.httpHeaderOptions = this.authSvc.getAuthHeader();
  }

  getRoles() {
    let url = this.config.baseUrl + 'dashboard/roles';
    return this.http.get(url, this.httpHeaderOptions);
  }

  getLogRetentions() {
    let url = this.config.baseUrl + 'dashboard/log_retention_periods';
    return this.http.get(url, this.httpHeaderOptions);
  }
}

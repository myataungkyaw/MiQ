import { Injectable } from '@angular/core';
import { environment } from '../../environments/environment';
import { prod_environment } from '../../environments/environment.prod';
import { HttpClient } from '@angular/common/http';
import { AuthService } from './auth.service';
@Injectable({
  providedIn: 'root'
})
export class LineService {
  private config:any;
  private httpHeaderOptions:any;
  constructor(private http:HttpClient, private authSvc:AuthService) { 
    this.config = (window.location.host.indexOf('localhost'))? prod_environment:environment;
    this.httpHeaderOptions = this.authSvc.getAuthHeader();
  }

  getAllLines() {
    let url = this.config.baseUrl + 'dashboard/lines';
    return this.http.get(url, this.httpHeaderOptions);
  }

  addLine(line){
    let url = this.config.baseUrl + 'dashboard/lines';
    return this.http.post(url, line, this.httpHeaderOptions);
  }

  updateLine(line, id){
    let url = this.config.baseUrl + 'dashboard/lines/'+id;
    return this.http.put(url, line, this.httpHeaderOptions);
  }

  getLineById(id){
    let url = this.config.baseUrl + 'dashboard/lines/'+id;
    return this.http.get(url, this.httpHeaderOptions);
  }

  getLineByCompany(companyId){
    let url = this.config.baseUrl + 'dashboard/lines/company/'+companyId;
    return this.http.get(url, this.httpHeaderOptions);
  }

  getDesksByCompany(companyId){
    let url = this.config.baseUrl + 'dashboard/lines/desks/'+companyId;
    return this.http.get(url, this.httpHeaderOptions);
  }

  getTags(){
    let url = this.config.baseUrl + 'dashboard/tags';
    return this.http.get(url, this.httpHeaderOptions);
  }



}

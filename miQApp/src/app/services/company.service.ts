import { Injectable } from '@angular/core';
// import { HttpClient } from '@angular/common/http';
import { HttpClient , HttpHeaders } from '@angular/common/http';
import { AuthService } from './auth.service';
import { environment } from '../../environments/environment';
import { prod_environment } from '../../environments/environment.prod';

@Injectable({
  providedIn: 'root'
})
export class CompanyService {
  private config:any;
  private httpHeaderOptions:any;
  constructor(private http:HttpClient, private auth:AuthService) { 
    this.config = (window.location.host.indexOf('localhost'))? prod_environment:environment;
    this.httpHeaderOptions = this.auth.getAuthHeader();
  }

  saveCompany(formData){
    let httpOptions = {
      headers: new HttpHeaders({ 
     //   'Content-Type': 'application/json',
        'Accept':'application/json',
        'Authorization': 'Bearer '+  localStorage.getItem("_access_token")
       })
   };

    let my_company = this.auth.getLoginCompany();
    console.log(my_company);

    let url = this.config.baseUrl + 'dashboard/companies/'+my_company.id;
    return this.http.post(url, formData, httpOptions);
   // return this.http.post(url, company, this.httpHeaderOptions);
  }

  getCompanyById(id){
    let url = this.config.baseUrl + 'dashboard/companies/'+id;
    return this.http.get(url);
  }

  uploadPhoto(id){
    let url = this.config.baseUrl + 'dashboard/companies/'+id;
    return this.http.post(url, {});
  }
}

import { Injectable } from '@angular/core';
import { environment } from '../../environments/environment';
import { prod_environment } from '../../environments/environment.prod';
import { HttpClient , HttpHeaders } from '@angular/common/http';


// const httpOptions = {
//   headers: new HttpHeaders({ 
//     'Content-Type': 'application/json',
//     'Authorization': 'Bearer '+localStorage.getItem("_access_token")
//  })
// };

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  private config: any;
  private token:any = localStorage.getItem("_access_token") || null;
  private loggedInUser:any = localStorage.getItem("_loggedInUser") ? JSON.parse(localStorage.getItem("_loggedInUser")) : {};
  private userCompanies:any = localStorage.getItem("_userCompanies") ? JSON.parse(localStorage.getItem("_userCompanies")) : {};
  private company:any = localStorage.getItem("_loggedInCompany") ? JSON.parse(localStorage.getItem("_loggedInCompany")) : {};
  constructor(private http:HttpClient) { 
    this.config = (window.location.host.indexOf('localhost'))? prod_environment:environment;
    this.token =  localStorage.getItem("_access_token") || null;
  }

  getAuthHeader(){

    let httpOptions = {
          headers: new HttpHeaders({ 
            'Content-Type': 'application/json',
            'Authorization': 'Bearer '+this.token
        })
    };
    return httpOptions;
  }

  getFormDataAuthHeader(){
    let httpOptions = {
          headers: new HttpHeaders({ 
            'Content-Type': 'application/x-www-form-urlencoded',
            'Authorization': 'Bearer '+this.token
        })
    };
    return httpOptions;
  }

  setToken(token){
    this.token = token;
    localStorage.setItem("_access_token", token);
  }

  getToken(){
    return this.token;
  }

  setLoggedInUser(user){
    this.loggedInUser = user;
    localStorage.setItem("_loggedInUser", JSON.stringify(user));
  }

  getLoggedInUser(){
    return this.loggedInUser;
  }

  setUserCompanies(companies){
    this.userCompanies = companies;
    localStorage.setItem("_userCompanies", JSON.stringify(companies));
  }

  getUserCompanies(){
    return this.userCompanies;
  }

  setLoginCompany(company){
    this.company = company;
    localStorage.setItem("_loggedInCompany", JSON.stringify(company));
  }

  getLoginCompany(){
    return this.company;
  }

  login(userObj) {
    let url = this.config.baseUrl + 'auth/login';
    return this.http.post(url, userObj);
  }

  logout(){
    this.loggedInUser = null;
    localStorage.removeItem("_access_token");
    localStorage.removeItem("_loggedInUser");
    localStorage.removeItem("_userCompanies");
    localStorage.removeItem("_loggedInCompany");
    let url =  this.config.baseUrl +"auth/logout";
    return this.http.post(url , {},  this.getAuthHeader());
  }

  refreshToken(){
    let url = this.config.baseUrl+"auth/refresh";
    return this.http.post(url, {}, this.getAuthHeader());
  }

  getAuthenticatedUser(){
    let url =  this.config.baseUrl +"auth/profile";
    return this.http.post(url , {}, this.getAuthHeader());
  }

  isLoggedIn(){
    return this.loggedInUser ? true : false;
  }

  fetchCompanies(){
    let url = this.config.baseUrl+"dashboard/companies";
    return this.http.get(url);
  }

}

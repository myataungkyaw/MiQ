import { Injectable } from '@angular/core';
import { environment } from '../../environments/environment';
import { prod_environment } from '../../environments/environment.prod';
import { HttpClient } from '@angular/common/http';
import { AuthService } from './auth.service';

@Injectable({
  providedIn: 'root'
})
export class UserService {

  private config: any;
  private httpHeaderOptions: any;
  constructor(private http:HttpClient, private authSvc:AuthService) { 
    this.config = (window.location.host.indexOf('localhost'))? prod_environment:environment;
    this.httpHeaderOptions = this.authSvc.getAuthHeader();
  }

  getAllUsers() {
    let url = this.config.baseUrl + 'dashboard/users';
    return this.http.get(url, this.httpHeaderOptions);
  }

  addUser(user){

    let company = this.authSvc.getLoginCompany();
    user.company_id = company.id;
    let url = this.config.baseUrl + 'dashboard/users';
    return this.http.post(url, user, this.httpHeaderOptions);
  }

  updateUser(user, id){
    let url = this.config.baseUrl + 'dashboard/users/'+id;
    return this.http.put(url, user, this.httpHeaderOptions);
  }

  getUserById(id){
    let url = this.config.baseUrl + 'dashboard/users/'+id;
    return this.http.get(url, this.httpHeaderOptions);
  }

  getUsers(query=null) {
    let qstr = "?";
    if(query){
        qstr += this.serialize(query);
    }
    let url = this.config.baseUrl + 'dashboard/users'+qstr;
    return this.http.get(url, this.httpHeaderOptions);
  }

  changePassword(changeUser, id){
    let url = this.config.baseUrl + 'dashboard/users/'+id+'/changePassword';
    return this.http.post(url, changeUser, this.httpHeaderOptions);
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

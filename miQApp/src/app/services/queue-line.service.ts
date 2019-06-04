import { Injectable } from '@angular/core';
import { environment } from '../../environments/environment';
import { prod_environment } from '../../environments/environment.prod';
import { HttpClient } from '@angular/common/http';
import { AuthService } from './auth.service';

@Injectable({
  providedIn: 'root'
})
export class QueueLineService {

  private config: any;
  private httpHeaderOptions: any;
  
  constructor(private http:HttpClient, private authSvc:AuthService) { 
    this.config = (window.location.host.indexOf('localhost'))? prod_environment:environment;
    this.httpHeaderOptions = this.authSvc.getAuthHeader();
  }

  getQueueLine(query){
    this.httpHeaderOptions = this.authSvc.getAuthHeader();
    
    let qstr = "?token="+this.authSvc.getToken()+"&";
    if (query) {
      qstr += this.serialize(query);
    }
    let url = this.config.baseUrl + "dashboard/queueLines/"+qstr;
    console.log(this.httpHeaderOptions);
    return this.http.get(url, this.httpHeaderOptions);
  }

  getCurrentQueueLine(query){

    let qstr = "?current=1&";
    if (query) {
      qstr += this.serialize(query);
    }
    let url = this.config.baseUrl + "dashboard/queueLines/"+qstr;
    return this.http.get(url, this.httpHeaderOptions);

  }

  updateQueueLine(obj){
    let url = this.config.baseUrl + "dashboard/queueLines/updateCallNumber";
    return this.http.post(url, obj, this.httpHeaderOptions);
  }

  returnQueueLine(id){
    let url = this.config.baseUrl + "dashboard/queueLines/return/"+id;
    return this.http.put(
      url,
      { id:id },
      this.httpHeaderOptions
    );
  }

  finishQueueLine(id){
    let url = this.config.baseUrl + "dashboard/queueLines/finish/"+id;
    return this.http.put(
      url,
      {},
      this.httpHeaderOptions
    );
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

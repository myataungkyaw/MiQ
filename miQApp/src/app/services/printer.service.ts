import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { AuthService } from './auth.service';
import { environment } from '../../environments/environment';
import { prod_environment } from '../../environments/environment.prod';

@Injectable({
  providedIn: 'root'
})
export class PrinterService {
 private config:any;
 public httpHeaderOptions:any;

  constructor(private http: HttpClient, 
    public authSvc: AuthService) { 

    this.config = (window.location.host.indexOf('localhost'))? prod_environment:environment;
    this.httpHeaderOptions = this.authSvc.getAuthHeader();
  }


  getAllPrinters() {
    let url = this.config.baseUrl + 'dashboard/printers';
    return this.http.get(url, this.httpHeaderOptions);
  }

  getAPrinter(id) {
    let url = this.config.baseUrl + 'dashboard/printers/'+id;
    return this.http.get(url, this.httpHeaderOptions);
  }

  addPrinter(printer){
    let url = this.config.baseUrl + 'dashboard/printers';
    return this.http.post(url, printer, this.httpHeaderOptions);
  }

  updatePrinter(printer, id){
    let url = this.config.baseUrl + 'dashboard/printers/'+id;
    return this.http.put(url, printer, this.httpHeaderOptions);
  }


}

import { Component, OnInit } from '@angular/core';
import { PrinterService } from '../../services/printer.service';
import { Router } from '@angular/router';
import { environment } from '../../../environments/environment';
import { HelperService } from 'src/app/services/helper.service';

@Component({
  selector: 'app-add-printer',
  templateUrl: './add-printer.component.html',
  styleUrls: ['./add-printer.component.css']
})
export class AddPrinterComponent implements OnInit {
  public printer:any = {};
  constructor(
    public printerSvc: PrinterService, 
    private helper: HelperService,
    private router: Router) { 

  }

  ngOnInit() {
  }

  savePrinter(f){

    if (f.valid ){
      this.printerSvc.addPrinter(this.printer).subscribe(
        (res:any)=>{
          this.printer={};
          this.helper.showSuccessMessage("Successfully created!");
          this.router.navigateByUrl(environment.urlPrefix+"/dashboard/printers");
     },
     (err)=>{
       alert("Error! Cannot add printer.");
      });
    }else{
      alert("Validation Error!");
    }
  }

}

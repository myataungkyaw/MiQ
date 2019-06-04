import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { PrinterService } from 'src/app/services/printer.service';
import { HelperService } from 'src/app/services/helper.service';
import { environment } from '../../../environments/environment';

@Component({
  selector: 'app-edit-printer',
  templateUrl: './edit-printer.component.html',
  styleUrls: ['./edit-printer.component.css']
})
export class EditPrinterComponent implements OnInit {
  public id:any = 0;
  public printer:any = {};
  constructor(
    private printerSvc: PrinterService, 
    private helper: HelperService, 
    private router: Router,
    private activeRouter : ActivatedRoute) { 

  }

  ngOnInit() {
    this.activeRouter.paramMap.subscribe(params => {
      this.id = params.get('id');
   });

   this.printerSvc.getAPrinter(this.id).subscribe((res:any)=>{
     this.printer = res.data;
   });

  }


  savePrinter(f){

    if (f.valid ){
      this.printerSvc.updatePrinter(this.printer, this.id).subscribe(
        (res:any)=>{
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

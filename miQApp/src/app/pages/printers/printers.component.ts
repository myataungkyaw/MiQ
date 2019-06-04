import { Component, OnInit } from '@angular/core';
import { PrinterService } from '../../services/printer.service';

@Component({
  selector: 'app-printers',
  templateUrl: './printers.component.html',
  styleUrls: ['./printers.component.css']
})
export class PrintersComponent implements OnInit {
 public printers:any = [];
  constructor(private printerSvc: PrinterService) { 

  }

  ngOnInit() {
    this.printerSvc.getAllPrinters().subscribe((res:any)=>{
      this.printers = res.data;
    });
  }

}

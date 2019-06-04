import { Component, OnInit } from '@angular/core';
import { environment } from '../../../environments/environment';

@Component({
  selector: 'app-display-setup',
  templateUrl: './display-setup.component.html',
  styleUrls: ['./display-setup.component.css']
})
export class DisplaySetupComponent implements OnInit {
  public env:any;
  constructor() {
    this.env = environment;
   }

  ngOnInit() {
  }

}

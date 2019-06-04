import { Injectable } from '@angular/core';
import Swal from 'sweetalert2';
import { Router } from '@angular/router';
import { AuthService } from './auth.service';
declare var jQuery;

@Injectable({
  providedIn: 'root'
})
export class HelperService {

  constructor(public router:Router, private auth:AuthService) { }
  public showErrorMessage() {
    'use strict';
    this.resetToastPosition();
    jQuery.toast({
        heading: 'Information',
        text: 'Connection Fail!',
        position: String("top-right"),
        icon: 'info',
        stack: false,
        loaderBg: '#f96868',
        bgColor:"#fb9678"
    })
  }

  public showSuccessMessage(Message) {
    'use strict';
    this.resetToastPosition();
    jQuery.toast({
        heading: 'Information',
        text: Message,
        position: String("top-right"),
        icon: 'info',
        stack: false,
        loaderBg: '#f96868',
        bgColor:"#00c292"
    })
  }

  public resetToastPosition() {
    jQuery('.jq-toast-wrap').removeClass('bottom-left bottom-right top-left top-right mid-center'); // to remove previous position class
    jQuery(".jq-toast-wrap").css({"top": "", "left": "", "bottom":"", "right": ""}); //to remove previous position style
  }

  public showLoginExpired(){
    return Swal.fire({
      title: 'Error!',
      text: 'Login Expired',
      type: 'error',
      confirmButtonText: 'OK'
    });
  }

  public generateQueueNumber(n) {
    let width = 5;
    let z = '0';
    n = n + '';
    let prefix = this.auth.getLoginCompany().queue_prefix;
    let number = n.length >= width ? n : new Array(width - n.length + 1).join(z) + n;
    return prefix+number;
  }


}

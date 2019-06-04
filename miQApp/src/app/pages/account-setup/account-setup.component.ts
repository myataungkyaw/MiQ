import { Component, OnInit } from "@angular/core";
import { MacroService } from "../../services/macro.service";
import { HelperService } from "../../services/helper.service";
import { Router } from "@angular/router";
import { CompanyService } from "src/app/services/company.service";
import { AuthService } from "src/app/services/auth.service";

declare var jQuery: any;
@Component({
  selector: "app-account-setup",
  templateUrl: "./account-setup.component.html",
  styleUrls: ["./account-setup.component.css"]
})
export class AccountSetupComponent implements OnInit {
  public logRetentionPeriods: any;
  public loading: any;
  public company: any = {};
  public background_image: any;
  public logo: any;
  public notification_sound:any;
  public display_video:any;

  constructor(
    private macro: MacroService,
    private helper: HelperService,
    private router: Router,
    private companySvc: CompanyService,
    private authSvc: AuthService
  ) {}

  ngOnInit() {
    this.macro.getLogRetentions().subscribe(
      (res: any) => {
        this.logRetentionPeriods = res.data;
      },
      err => {
        this.loading = false;
        this.helper.showErrorMessage();
      }
    );

    let company = this.authSvc.getLoginCompany();
    if (company.id) {
      this.companySvc.getCompanyById(company.id).subscribe(
        (res: any) => {
          if (res.data) {
            this.company = res.data;
          }
        },
        (err: any) => {
          console.log(err);
        }
      );
    }
  }

  ngAfterViewInit() {
    (function($) {
      "use strict";
      $(function() {
        $(".file-upload-default").on("change", function() {
          $(this)
            .parent()
            .find(".form-control")
            .val(
              $(this)
                .val()
                .replace(/C:\\fakepath\\/i, "")
            );
        });
      });
    })(jQuery);
  }

  onLogoChoose(event) {
    this.logo = event.target.files[0];
    
  }

  onBackgroundChoose(event) {
    this.background_image = event.target.files[0];
  }

  onNotiChoose(event){
   this.notification_sound = event.target.files[0];
  }

  onVideoChoose(event){
    this.display_video = event.target.files[0];
  }


  saveCompany(companyForm) {
    if (companyForm.valid) {
      console.log(this.company);

      let companyObj = new FormData();
      if (this.background_image != null) {
        companyObj.append(
          "background_image",
          this.background_image,
          this.background_image.name
        );
      }

      if (this.logo != null) {
        companyObj.append("logo", this.logo, this.logo.name);
      }

      if (this.notification_sound != null) {
        companyObj.append("notification_sound", this.notification_sound, this.notification_sound.name);
      }

      companyObj.append("name", this.company.name);
      companyObj.append("address", this.company.address);
      companyObj.append(
        "log_retention_period",
        this.company.log_retention_period
      );
      companyObj.append("queue_prefix", this.company.queue_prefix);
      companyObj.append("note", this.company.note);
      companyObj.append("third_party_integration", "0");
      if(this.company.scrolling_text!=null){
        companyObj.append("scrolling_text", this.company.scrolling_text);
      }
     
      console.log(this.company);
      // console.log(companyObj);

      this.companySvc.saveCompany(companyObj).subscribe(
        (res: any) => {

          if(res.data){

            this.helper.showSuccessMessage("Successfully Saved Company Info.");
            //update cache 
            console.log(res.data);
            this.authSvc.setLoginCompany(res.data)
            this.router.navigate(["dashboard"]);

          }
         
        },
        err => {
          this.loading = false;
          this.helper.showErrorMessage();
        }
      );
    }
  }
}

import { Component, OnInit } from '@angular/core';
import { LineService } from "../../services/line.service";
import { HelperService } from "../../services/helper.service";
import { Router } from '@angular/router';
import { AuthService } from 'src/app/services/auth.service';
declare var jQuery;

@Component({
  selector: 'app-add-line',
  templateUrl: './add-line.component.html',
  styleUrls: ['./add-line.component.css']
})
export class AddLineComponent implements OnInit {
  public line:any = {};
  public lineDesks:any = [];
  private loading:boolean = false;
  public tags_list:any = [];
  public tags:any = [];
  constructor(
    private lineSvc:LineService, 
    private helper:HelperService, 
    private auth:AuthService,
    private router:Router) { 

    }
    
  ngOnInit() {
    this.line.color='#f0f3bd';
    this.lineSvc.getTags().subscribe((res:any)=>{
       this.tags_list = res.data;
    });

  }

  ngAfterViewInit(){
    var that = this;
    (function($) {
      'use strict';

      jQuery(".js-example-basic-multiple").select2({tags: true});

      jQuery(function() {
        var todoListItem = jQuery('.todo-list');
        var todoListInput = jQuery('.todo-list-input');
        jQuery('.todo-list-add-btn').on("click", function (event) {
          event.preventDefault();
    
        var item = jQuery(this).prevAll('.todo-list-input').val();
    
          if(item)
          {
            that.lineDesks.push({id:0, name:item});
            todoListItem.append("<li><label class='col-form-label'><span class='desks'>" + item + "</span><i class='input-helper'></i></label><i class='remove mdi mdi-close-circle-outline'></i></li>");
            todoListInput.val("");
          }
    
        });
    
        todoListItem.on('click', '.remove', function()
        {
          const filteredItems = that.lineDesks.filter(item => item.name !== jQuery(this).siblings().text())
          that.lineDesks = filteredItems;
          jQuery(this).parent().remove();
        });
    
      });


      if (jQuery(".color-picker").length) {
        var colorPicker = jQuery('.color-picker').asColorPicker();

      }
     
    })(jQuery);

    
  }

  saveLine(lineForm:any){
    if (lineForm.valid ){
      var loginCompany = this.auth.getLoginCompany();
      let line = lineForm.value;
      let color = jQuery('#color').val();
      let tags = jQuery("#tags").select2('data');
      this.tags = [];
      for(var i = 0; i<tags.length; i++){
        this.tags.push({text:tags[i].text});
      }
      let lineObj = {name:line.name, color:color, priority:line.priority, tags: this.tags,  desks:this.lineDesks, company_id:loginCompany.id}
      this.lineSvc.addLine(lineObj).subscribe((res:any)=>{
      this.helper.showSuccessMessage('Saved Line Successfully');
      this.router.navigate(["/dashboard/lines"]);
      }, (err)=>{
        this.loading = false;
        this.helper.showErrorMessage();
      });
    }

  }

}

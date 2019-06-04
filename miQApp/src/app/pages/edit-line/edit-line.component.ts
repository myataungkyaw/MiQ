import { Component, OnInit } from '@angular/core';
import { LineService } from "../../services/line.service";
import { HelperService } from "../../services/helper.service";
import { Router, ActivatedRoute } from '@angular/router';
declare var jQuery;

@Component({
  selector: 'app-edit-line',
  templateUrl: './edit-line.component.html',
  styleUrls: ['./edit-line.component.css']
})
export class EditLineComponent implements OnInit {
  public id:any = 0;
  public line:any = {};
  public lineDesks:any = [];
  public loading:boolean= false;
  public tags_list:any = [];
  public tags:any = [];

  constructor(
    private lineSvc:LineService, 
    private helper:HelperService, 
    private router:Router,
    private activeRouter:ActivatedRoute
    ) { 

    }

  ngOnInit() {
    this.activeRouter.paramMap.subscribe(params => {
      this.id = params.get('id');
   });
    
    this.getLine(this.id);

    this.lineSvc.getTags().subscribe((res:any)=>{
      this.tags_list = res.data;
   });


  }

  ngAfterViewInit(){
    var that = this;

    // jQuery(".js-example-basic-multiple").select2({tags: true}).val(["one","two"]).trigger('change');
    // console.log(this.line.tags);
    //  alert("yo");
  
    
    (function($) {
      'use strict';
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
        jQuery('.color-picker').asColorPicker();
      }
      
    })(jQuery);
  }

  getLine(id){
    var that = this;
    this.lineSvc.getLineById(id).subscribe((res:any)=>{
      this.line = res.data;
      this.line.line_desks.forEach(element => {
        that.lineDesks.push({id:element.id, name:element.name});
      });
      

    }, (err)=>{
      this.loading = false;
      this.helper.showErrorMessage();
    });
  }

  updateLine(lineForm){
    if (lineForm.valid ){

      let line = lineForm.value;
      let color = jQuery('#color').val();
      let tags = jQuery("#tags").select2('data');
      this.tags = [];
      for(var i = 0; i<tags.length; i++){
        this.tags.push({text:tags[i].text});
      }

      let lineObj = {name:line.name, color:color, tags: this.tags, priority:line.priority, desks:this.lineDesks};
      this.lineSvc.updateLine(lineObj, this.id).subscribe((res:any)=>{
        this.helper.showSuccessMessage('Update Line Successfully');
        this.router.navigate(["/dashboard/lines"]);
      }, (err)=>{
        this.loading = false;
        this.helper.showErrorMessage();
      });
    }
  }

}

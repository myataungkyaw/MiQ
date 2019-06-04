import { Injectable } from "@angular/core";
import { HttpClient } from "@angular/common/http";
import { AuthService } from "./auth.service";
import { environment } from "../../environments/environment";
import { prod_environment } from "../../environments/environment.prod";

@Injectable({
  providedIn: "root"
})
export class QueueService {
  private config: any;
  private httpHeaderOptions: any;
  constructor(private http: HttpClient, private auth: AuthService) {
    this.config = window.location.host.indexOf("localhost")
      ? prod_environment
      : environment;
    this.httpHeaderOptions = auth.getAuthHeader();
  }

  saveIssue(issue) {
    let url = this.config.baseUrl + "dashboard/queues";
    return this.http.post(url, issue, this.httpHeaderOptions);
  }

  getOneQueue(id) {
    let url = this.config.baseUrl + "dashboard/queues/" + id;
    return this.http.get(url, this.httpHeaderOptions);
  }

  getQueue(query) {
    let qstr = "?";
    if (query) {
      qstr += this.serialize(query);
    }
    let url = this.config.baseUrl + "dashboard/queues/" + qstr;
    return this.http.get(url, this.httpHeaderOptions);
  }

  checkQueue(query) {
    let qstr = "?";
    if (query) {
      qstr += this.serialize(query);
    }
    let url = this.config.baseUrl + "dashboard/queues_search" + qstr;
    return this.http.get(url, this.httpHeaderOptions);
  }



  serialize(obj) {
    var str = [];
    for (var p in obj)
      if (obj.hasOwnProperty(p) && obj[p]) {
        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
      }
    return str.join("&");
  }


  /**
   * 
   * @param queue_id 
   * @param line_id 
   * @param status 1 = SERVING
   */
  serveQueue(queue_line_id , line_desk_id) {
    let url = this.config.baseUrl + "dashboard/queueLines/serveQueueLine/"+queue_line_id;
    return this.http.put(
      url,
      {line_desk_id:line_desk_id},
      this.httpHeaderOptions
    );
  }


  /**
   * 
   * @param queue_id 
   * @param line_id 
   * @param status 2 = DONE
   */
  finishQueue(queue_id, line_id) {
    let url = this.config.baseUrl + "dashboard/queueLines/updateQueueLine";
    return this.http.put(
      url,
      { line_id: line_id, status: 2, queue_id: queue_id },
      this.httpHeaderOptions
    );
  }

  addQueueLine(line_id, queue_id, position) {
    let url = this.config.baseUrl + "dashboard/queueLines/addQueueLine";
    return this.http.post(
      url,
      { line_id: line_id, position: position, queue_id: queue_id },
      this.httpHeaderOptions
    );
  }

  updateQueuePosition(queueLines){
    let url = this.config.baseUrl + "dashboard/queueLines/updateQueuePosition";
    return this.http.put(
      url,
      { queueLines: queueLines },
      this.httpHeaderOptions
    );
  }

  /**
   * 
   * @param queueLine 
   * @param status 3 = NO_SHOW
   */
  cancelQueue(queueLine){
    let url = this.config.baseUrl + "dashboard/queueLines/changeStatus";
    return this.http.put(
      url,
      { queueLine: queueLine[0], status:3 },
      this.httpHeaderOptions
    );
  }

  noShowQueue(queue_line_id){
    let url = this.config.baseUrl + "dashboard/queueLines/noshowQueueLine/"+queue_line_id;
    return this.http.put(
      url,
      { },
      this.httpHeaderOptions
    );
  }
}

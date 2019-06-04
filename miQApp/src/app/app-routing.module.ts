import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { HomeComponent } from './pages/home/home.component';
import { IssueQComponent } from './pages/issue-q/issue-q.component';
import { AssignQComponent } from './pages/assign-q/assign-q.component';
import { CallQComponent } from './pages/call-q/call-q.component';
import { CheckQComponent } from './pages/check-q/check-q.component';
import { ProfileSetupComponent } from './pages/profile-setup/profile-setup.component';
import { DataInsightComponent } from './pages/data-insight/data-insight.component';
import { UsersComponent } from './pages/users/users.component';
import { LinesComponent } from './pages/lines/lines.component';
import { LicenseInfoComponent } from './pages/license-info/license-info.component';
import { AuditLogsComponent } from './pages/audit-logs/audit-logs.component';
import { AccountSetupComponent } from './pages/account-setup/account-setup.component';
import { AddUserComponent } from './pages/add-user/add-user.component';
import { AddLineComponent } from './pages/add-line/add-line.component';
import { EditUserComponent } from './pages/edit-user/edit-user.component';
import { EditLineComponent } from './pages/edit-line/edit-line.component';
import { DashboardComponent } from './layouts/dashboard/dashboard.component';
import { PublicComponent } from './layouts/public/public.component';
import { LoginComponent } from './pages/login/login.component';
import { PublicGuard } from './public.guard';
import { AuthGuard } from './auth.guard';
import { PublicIssueQComponent } from './pages/public-issue-q/public-issue-q.component';
import { UserCompaniesComponent } from './pages/user-companies/user-companies.component';
import { PublicCheckQComponent } from './pages/public-check-q/public-check-q.component';
import { DisplayScreenComponent } from './pages/display-screen/display-screen.component';
import { RoomScreenComponent } from './pages/room-screen/room-screen.component';
import { DisplaySetupComponent } from './pages/display-setup/display-setup.component';
import { PrintersComponent } from './pages/printers/printers.component';
import { AddPrinterComponent } from './pages/add-printer/add-printer.component';
import { EditPrinterComponent } from './pages/edit-printer/edit-printer.component';


const publicRoutes: Routes = [
  {path:"", canActivate: [PublicGuard], redirectTo:"login",pathMatch:"full"},
  {path:"issue_q",  canActivate: [AuthGuard],  component: PublicIssueQComponent},
  {path:"check_q", canActivate: [AuthGuard],  component: PublicCheckQComponent},
  {path:"screen", canActivate: [AuthGuard], component: DisplayScreenComponent},
  {path:"room_screen", canActivate: [AuthGuard], component: RoomScreenComponent},
  {path:"login", canActivate: [PublicGuard], component: LoginComponent},
  {path:"display_setup", canActivate: [AuthGuard], component: DisplaySetupComponent},
  {path:"user_companies", component: UserCompaniesComponent},
  
];

const secureRoutes: Routes = [
  {path:"", component: HomeComponent},
  {path:"issue_q", component: IssueQComponent},
  {path:"assign_q", component: AssignQComponent},
  {path:"call_q", component: CallQComponent},
  {path:"check_q", component: CheckQComponent},
  {path:"profile_setup", component: ProfileSetupComponent},
  {path:"data_insight", component: DataInsightComponent},
  {path:"users", component: UsersComponent},
  {path:"lines", component: LinesComponent},
  {path:"license_info", component: LicenseInfoComponent},
  {path:"audit_logs", component: AuditLogsComponent},
  {path:"account_setup", component: AccountSetupComponent},
  {path:"add_user", component: AddUserComponent},
  {path:"add_line", component: AddLineComponent},
  {path:"edit_user/:id", component: EditUserComponent},
  {path:"edit_line/:id", component: EditLineComponent},
  {path:"printers", component: PrintersComponent},
  {path:"add_printer", component: AddPrinterComponent},
  {path:"edit_printer/:id", component: EditPrinterComponent},
 
];

const routes: Routes = [
  {path:"", component: PublicComponent,  children: publicRoutes},
  {path:"dashboard", component: DashboardComponent, canActivate:[AuthGuard], children: secureRoutes},
];


@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }

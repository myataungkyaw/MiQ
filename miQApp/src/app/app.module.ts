import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { HttpClientModule } from '@angular/common/http';
import { FormsModule }   from '@angular/forms';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { HomeComponent } from './pages/home/home.component';
import { CallQComponent } from './pages/call-q/call-q.component';
import { IssueQComponent } from './pages/issue-q/issue-q.component';
import { AssignQComponent } from './pages/assign-q/assign-q.component';
import { CheckQComponent } from './pages/check-q/check-q.component';
import { ProfileSetupComponent } from './pages/profile-setup/profile-setup.component';
import { DataInsightComponent } from './pages/data-insight/data-insight.component';
import { UsersComponent } from './pages/users/users.component';
import { LinesComponent } from './pages/lines/lines.component';
import { LicenseInfoComponent } from './pages/license-info/license-info.component';
import { AuditLogsComponent } from './pages/audit-logs/audit-logs.component';
import { AccountSetupComponent } from './pages/account-setup/account-setup.component';
import { HeaderComponent } from './common/header/header.component';
import { FooterComponent } from './common/footer/footer.component';
import { SidebarComponent } from './common/sidebar/sidebar.component';
import { NavbarComponent } from './common/navbar/navbar.component';
import { DashboardComponent } from './layouts/dashboard/dashboard.component';
import { PublicComponent } from './layouts/public/public.component';
import { AddUserComponent } from './pages/add-user/add-user.component';
import { AddLineComponent } from './pages/add-line/add-line.component';
import { EditUserComponent } from './pages/edit-user/edit-user.component';
import { EditLineComponent } from './pages/edit-line/edit-line.component';
import { LoginComponent } from './pages/login/login.component';
import { PublicIssueQComponent } from './pages/public-issue-q/public-issue-q.component';
import { PublicCheckQComponent } from './pages/public-check-q/public-check-q.component';
import { UserCompaniesComponent } from './pages/user-companies/user-companies.component';
import { DisplayScreenComponent } from './pages/display-screen/display-screen.component';
import { RoomScreenComponent } from './pages/room-screen/room-screen.component';
import { DisplaySetupComponent } from './pages/display-setup/display-setup.component';
import { PrintersComponent } from './pages/printers/printers.component';
import { AddPrinterComponent } from './pages/add-printer/add-printer.component';
import { EditPrinterComponent } from './pages/edit-printer/edit-printer.component';

@NgModule({
  declarations: [
    AppComponent,
    HomeComponent,
    CallQComponent,
    IssueQComponent,
    AssignQComponent,
    CheckQComponent,
    ProfileSetupComponent,
    DataInsightComponent,
    UsersComponent,
    LinesComponent,
    LicenseInfoComponent,
    AuditLogsComponent,
    AccountSetupComponent,
    HeaderComponent,
    FooterComponent,
    SidebarComponent,
    NavbarComponent,
    DashboardComponent,
    PublicComponent,
    AddUserComponent,
    AddLineComponent,
    EditUserComponent,
    EditLineComponent,
    LoginComponent,
    PublicIssueQComponent,
    PublicCheckQComponent,
    UserCompaniesComponent,
    DisplayScreenComponent,
    RoomScreenComponent,
    DisplaySetupComponent,
    PrintersComponent,
    AddPrinterComponent,
    EditPrinterComponent,
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    HttpClientModule,
    FormsModule
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }

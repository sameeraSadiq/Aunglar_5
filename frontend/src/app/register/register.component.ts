// register.component.ts
import { Component } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { NgForm } from '@angular/forms';

// Define the interface for the response structure
interface RegisterResponse {
  message: string;
  user?: {
    username: string;
    email: string;
  };
}

@Component({
  selector: 'app-register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.css']
})
export class RegisterComponent {
  model = {
    username: '',
    email: '',
    password: ''
  };
  message: string = '';
  registeredUser: any;
  constructor(private http: HttpClient) {}

  onSubmit() {
    if (this.model.username && this.model.email && this.model.password) {
      this.http.post<RegisterResponse>('http://localhost/afternoonPHP%2012-1/register.php', this.model)
        .subscribe(response => {
          this.message = response.message; 
          this.registeredUser = response.user; 
          console.log('User registered', response);
        }, error => {
          this.message = 'Error registering user: ' + error.message;
          console.error('Error registering user', error);
        });
    }
  }
}

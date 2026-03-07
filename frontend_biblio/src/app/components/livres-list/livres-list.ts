import { Component, inject, signal, OnInit } from '@angular/core';
import { DatePipe } from '@angular/common';
import { ApiService } from '../../services/api-service';
import { Livre } from '../../models/livre';

@Component({
  selector: 'app-livres-list',
  imports: [DatePipe],
  templateUrl: './livres-list.html'
})
export class LivresList implements OnInit {

  private apiService = inject(ApiService);

  livres = signal<Livre[]>([]);

  ngOnInit() {
    this.apiService.getLivres().subscribe(data => {
      this.livres.set(data);
    });
  }
}
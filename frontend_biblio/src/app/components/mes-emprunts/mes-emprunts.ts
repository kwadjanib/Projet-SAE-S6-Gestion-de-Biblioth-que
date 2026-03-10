import { Component, inject, signal, OnInit } from '@angular/core';
import { DatePipe } from '@angular/common';
import { ApiService } from '../../services/api-service';
import { Emprunt } from '../../models/adherent';

@Component({
  selector: 'app-mes-emprunts',
  imports: [DatePipe],
  templateUrl: './mes-emprunts.html',
  styleUrl: './mes-emprunts.css'
})
export class MesEmprunts implements OnInit {

  private apiService = inject(ApiService);

  emprunts = signal<Emprunt[]>([]);
  loading = signal(true);
  erreur = signal('');

  ngOnInit() {
    this.apiService.getMesEmprunts().subscribe({
      next: (data) => {
        this.emprunts.set(data);
        this.loading.set(false);
      },
      error: () => {
        this.erreur.set('Impossible de charger vos emprunts.');
        this.loading.set(false);
      }
    });
  }
}

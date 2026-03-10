import { Component, inject, signal, OnInit } from '@angular/core';
import { DatePipe } from '@angular/common';
import { ApiService } from '../../services/api-service';
import { Reservation } from '../../models/adherent';

@Component({
  selector: 'app-mes-reservations',
  imports: [DatePipe],
  templateUrl: './mes-reservations.html',
  styleUrl: './mes-reservations.css'
})
export class MesReservations implements OnInit {

  private apiService = inject(ApiService);

  reservations = signal<Reservation[]>([]);
  loading = signal(true);
  erreur = signal('');

 
  annulationEnCours = signal<number | null>(null);
  messageAnnulation = signal('');

  ngOnInit() {
    this.chargerReservations();
  }

  chargerReservations() {
    this.loading.set(true);
    this.apiService.getMesReservations().subscribe({
      next: (data) => {
        this.reservations.set(data);
        this.loading.set(false);
      },
      error: () => {
        this.erreur.set('Impossible de charger vos réservations.');
        this.loading.set(false);
      }
    });
  }

  annuler(reservationId: number) {
    this.annulationEnCours.set(reservationId);
    this.messageAnnulation.set('');

    this.apiService.annulerReservation(reservationId).subscribe({
      next: () => {
        this.reservations.update(list => list.filter(r => r.id !== reservationId));
        this.annulationEnCours.set(null);
        this.messageAnnulation.set('Réservation annulée avec succès.');
      },
      error: () => {
        this.annulationEnCours.set(null);
        this.messageAnnulation.set('Erreur lors de l\'annulation.');
      }
    });
  }
}

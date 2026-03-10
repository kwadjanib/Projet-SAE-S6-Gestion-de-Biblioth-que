import { Component, inject, signal, OnInit } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { ApiService } from '../../services/api-service';
import { AuthService } from '../../services/auth.service';
import { Adherent } from '../../models/adherent';

@Component({
  selector: 'app-mon-profil',
  imports: [FormsModule],
  templateUrl: './mon-profil.html',
  styleUrl: './mon-profil.css'
})
export class MonProfil implements OnInit {

  private apiService = inject(ApiService);
  private authService = inject(AuthService);

  profil = signal<Adherent | null>(null);
  loading = signal(true);
  saving = signal(false);
  succes = signal('');
  erreur = signal('');

  email = '';
  numTel = '';
  adressePostale = '';

  ngOnInit() {
    this.apiService.getProfil().subscribe({
      next: (data) => {
        this.profil.set(data);
        this.email = data.email;
        this.numTel = data.numTel ?? '';
        this.adressePostale = data.adressePostale ?? '';
        this.loading.set(false);
      },
      error: () => {
        this.erreur.set('Impossible de charger votre profil.');
        this.loading.set(false);
      }
    });
  }

  sauvegarder() {
    this.saving.set(true);
    this.succes.set('');
    this.erreur.set('');

    this.apiService.updateProfil({
      email: this.email,
      numTel: this.numTel,
      adressePostale: this.adressePostale
    }).subscribe({
      next: (data) => {
        this.profil.set(data);
        this.saving.set(false);
        this.succes.set('Profil mis à jour avec succès !');
      },
      error: () => {
        this.saving.set(false);
        this.erreur.set('Erreur lors de la mise à jour.');
      }
    });
  }
}

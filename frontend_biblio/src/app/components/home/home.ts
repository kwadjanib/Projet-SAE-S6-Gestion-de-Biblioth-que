import { Component, inject, signal, OnInit } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { DatePipe } from '@angular/common';
import { RouterLink } from '@angular/router';
import { ApiService } from '../../services/api-service';
import { Livre } from '../../models/livre';
import { Categorie } from '../../models/categorie';

@Component({
  selector: 'app-home',
  imports: [FormsModule, DatePipe, RouterLink],
  templateUrl: './home.html',
  styleUrl: './home.css'
})
export class Home implements OnInit {

  private apiService = inject(ApiService);

  livres = signal<Livre[]>([]);
  categories = signal<Categorie[]>([]);

  titre = '';
  categorieId = '';

  ngOnInit() {

    this.apiService.getCategories().subscribe(data => {
      this.categories.set(data);
    });

    this.loadLivres();
  }

  loadLivres() {
    this.apiService.getLivres().subscribe(data => {
      this.livres.set(data);
    });
  }

  rechercher() {

    const params: any = {};

    if (this.titre) params.titre = this.titre;
    if (this.categorieId) params.categorie = this.categorieId;

    this.apiService.rechercherLivres(params).subscribe(data => {
      this.livres.set(data);
    });

  }

  getCouverture(livre: Livre) {
    return livre.photoCouverture || this.getFallbackCover(livre.id);
  }

  getAuteursLabel(livre: Livre) {
    const auteurs = livre.auteurs ?? [];
    if (!auteurs.length) return 'Auteur inconnu';
    return auteurs
      .map(a => this.formatAuteur(a))
      .filter(Boolean)
      .join(', ');
  }

  private formatAuteur(auteur: { prenom?: string | null; nom?: string | null }) {
    const prenom = auteur.prenom?.trim();
    const nom = auteur.nom?.trim();
    return [prenom, nom].filter(Boolean).join(' ') || nom || '';
  }

  private getFallbackCover(id?: number) {
    const covers = [
      '/images/covers/cover-1.svg',
      '/images/covers/cover-2.svg',
      '/images/covers/cover-3.svg',
      '/images/covers/cover-4.svg',
      '/images/covers/cover-5.svg',
      '/images/covers/cover-6.svg'
    ];
    if (!id) return covers[0];
    return covers[Math.abs(id) % covers.length];
  }

}

import { Component, inject, signal, OnInit } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { ApiService } from '../../services/api-service';
import { Livre } from '../../models/livre';
import { Categorie } from '../../models/categorie';

@Component({
  selector: 'app-home',
  imports: [FormsModule],
  templateUrl: './home.html'
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

}
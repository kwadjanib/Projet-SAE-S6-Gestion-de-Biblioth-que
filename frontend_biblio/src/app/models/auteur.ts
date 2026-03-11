export interface AuteurLivre {
  id: number;
  titre: string;
  dateSortie?: string;
  photoCouverture?: string;
}

export interface Auteur {
  id: number;
  nom: string;
  prenom?: string;
  dateNaissance?: string;
  dateDeces?: string;
  nationalite?: string;
  photo?: string;
  description?: string;
  livre?: AuteurLivre[];
}

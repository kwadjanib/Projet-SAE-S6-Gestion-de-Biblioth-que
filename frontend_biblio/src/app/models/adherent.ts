export interface Adherent {
  id: number;
  nom: string;
  prenom: string;
  email: string;
  numTel?: string;
  adressePostale?: string;
  dateAdhesion?: string;
  dateNaiss?: string;
  photo?: string;
}

export interface Emprunt {
  id: number;
  livre: {
    id: number;
    titre: string;
    photoCouverture?: string;
  };
  dateEmprunt: string;
  dateRetour: string;
  enRetard?: boolean;
}

export interface Reservation {
  id: number;
  livre: {
    id: number;
    titre: string;
    photoCouverture?: string;
  };
  dateResa: string;
}

export interface LoginDto {
  email: string;
  password: string;
}

export interface AuthResponse {
  token: string;
}

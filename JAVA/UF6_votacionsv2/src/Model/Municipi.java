package Model;

import java.util.Objects;

public class Municipi {
	private long codi;
	private String nom;
	
    public Municipi(String nom) {
    	this.nom = nom;
    }

	public Municipi(long codi, String nom) {
		super();
		this.codi = codi;
		this.nom = nom;
	}
	public long getCodi() {
		return codi;
	}
	public void setCodi(long codi) {
		this.codi = codi;
	}
	public String getNom() {
		return nom;
	}
	public void setNom(String nom) {
		this.nom = nom;
	}
	@Override
	public String toString() {
		return "Municipi [codi=" + codi + ", nom=" + nom + "]";
	}

	@Override
	public int hashCode() {
		return Objects.hash(codi, nom);
	}

	@Override
	public boolean equals(Object obj) {
		if (this == obj)
			return true;
		if (obj == null)
			return false;
		if (getClass() != obj.getClass())
			return false;
		Municipi other = (Municipi) obj;
		return codi == other.codi && Objects.equals(nom, other.nom);
	}
	
	
}

package Model;

import java.util.ArrayList;
import java.util.Objects;

public class Resultat {
	private Partit partit;
	private Municipi municipi;
	private double porcentajeVotos;

	public Resultat() {}
	public Resultat(Partit partit, Municipi municipi, double porcentajeVotos) {
		this.partit = partit;
		this.municipi = municipi;
		this.porcentajeVotos = porcentajeVotos;
	}

	public Partit getPartit() {
		return partit;
	}

	public void setPartit(Partit partit) {
		this.partit = partit;
	}

	public Municipi getMunicipi() {
		return municipi;
	}

	public void setMunicipi(Municipi municipi) {
		this.municipi = municipi;
	}

	public double getPorcentajeVotos() {
		return porcentajeVotos;
	}

	public void setPorcentajeVotos(double porcentajeVotos) {
		this.porcentajeVotos = porcentajeVotos;
	}

	@Override
	public String toString() {
		return "Resultado [partit=" + partit.getSiglas() + ", municipi=" + municipi.getNom() + ", vots="
				+ porcentajeVotos + "]";
	}

	@Override
	public int hashCode() {
		return Objects.hash(municipi, partit, porcentajeVotos);
	}

	@Override
	public boolean equals(Object obj) {
		if (this == obj)
			return true;
		if (obj == null)
			return false;
		if (getClass() != obj.getClass())
			return false;
		Resultat other = (Resultat) obj;
		return Objects.equals(municipi, other.municipi) && Objects.equals(partit, other.partit)
				&& Double.doubleToLongBits(porcentajeVotos) == Double.doubleToLongBits(other.porcentajeVotos);
	}

}

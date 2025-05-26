package Model;

import java.util.Objects;

public class Partit {
	private int any;
	private String siglas;
	private int vots;
	private int regidores;
	private Municipi municipi;

	public Partit(int any, String siglas, int vots, int regidores, Municipi municipi) {
		super();
		this.any = any;
		this.siglas = siglas;
		this.vots = vots;
		this.regidores = regidores;
        this.municipi = municipi;
	}
	
	

	public Municipi getMunicipi() {
		return municipi;
	}

	public void setMunicipi(Municipi municipi) {
		this.municipi = municipi;
	}

	public int getAny() {
		return any;
	}

	public void setAny(int any) {
		this.any = any;
	}

	public String getSiglas() {
		return siglas;
	}

	public void setSiglas(String siglas) {
		this.siglas = siglas;
	}

	public int getVots() {
		return vots;
	}

	public void setVots(int vots) {
		this.vots = vots;
	}

	public int getRegidores() {
		return regidores;
	}

	public void setRegidores(int regidores) {
		this.regidores = regidores;
	}

	@Override
	public String toString() {
		return "Partit [any=" + any + ", siglas=" + siglas + ", vots=" + vots + ", regidores=" + regidores + "]";
	}



	@Override
	public int hashCode() {
		return Objects.hash(any, municipi, regidores, siglas, vots);
	}

	@Override
	public boolean equals(Object obj) {
		if (this == obj)
			return true;
		if (obj == null)
			return false;
		if (getClass() != obj.getClass())
			return false;
		Partit other = (Partit) obj;
		return any == other.any && Objects.equals(municipi, other.municipi) && regidores == other.regidores
				&& Objects.equals(siglas, other.siglas) && vots == other.vots;
	}
	
	
}

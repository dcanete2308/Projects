document.addEventListener("DOMContentLoaded", () => {
	const sections = document.querySelectorAll(".form-section");
	const nextButtons = document.querySelectorAll(".next-button");
	const prevButtons = document.querySelectorAll(".prev-button");
	let currentSection = 0;

	function showSection(index) {
		sections.forEach((section, i) => {
			section.style.display = i === index ? "block" : "none";
		});
	}

	nextButtons.forEach((button, index) => {
		button.addEventListener("click", () => {
			if (currentSection < sections.length - 1) {
				currentSection++;
				showSection(currentSection);
			}
		});
	});

	prevButtons.forEach((button, index) => {
		button.addEventListener("click", () => {
			if (currentSection > 0) {
				currentSection--;
				showSection(currentSection);
			}
		});
	});

	showSection(currentSection);
});
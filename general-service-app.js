const element = document.getElementById("general-service-app");
if (!element) {
  throw new Error("Element with ID 'general-service-app' not found.");
}

const types = ["news", "business", "resources"];

const labels = {
  news: {
    en: "News",
    es: "Noticias",
    fr: "Nouvelles",
  },
  business: {
    en: "Business",
    es: "Negocios",
    fr: "Affaires",
  },
  resources: {
    en: "Resources",
    es: "Recursos",
    fr: "Ressources",
  },
};

const languages = ["en", "es", "fr"];

const now = new Date();

const dev = window.location.origin.endsWith(".test");

const host = dev ? "general-service-app-backend.test" : "generalservice.app";

const url = `https://${host}/storage/106.json`;

fetch(url)
  .then((response) => {
    if (!response.ok) {
      throw new Error("Network response was not ok");
    }
    return response.json();
  })
  .then((data) => {
    // get area stories
    const stories = data[1].stories.filter(({ start_at, end_at }) => {
      const startDate = new Date(start_at);
      const endDate = new Date(end_at);
      return now >= startDate && now <= endDate;
    });

    // determine language
    const language = element.getAttribute("data-language");

    if (!languages.includes(language)) {
      throw new Error(`Unsupported language: ${language}`);
    }

    const filteredStories = stories.filter((s) => s.language === language);

    if (!filteredStories.length) {
      throw new Error(`Story not found for language: ${language}`);
    }

    element.innerHTML = types
      .filter(
        (type) => filteredStories.filter((s) => s.type === type).length > 0
      )
      .map((type) => {
        return `<h2>${labels[type][language]}</h2>${filteredStories
          .filter((s) => s.type === type)
          .map(
            (story) => `<article><h3>${story.title}</h3><p>${
              story.description
            }</p>
            ${
              story.buttons.filter((button) => button.type === "link").length >
              0
                ? `<ul>${story.buttons
                    .filter((button) => button.type === "link")
                    .map(
                      (button) =>
                        `<li><a href="${button.link}" target="_blank" rel="noopener">${button.title}</a></li>`
                    )
                    .join("")}</ul>`
                : ""
            }
            </article>`
          )
          .join("")}`;
      })
      .join("");

    element.classList.add("loaded");
  })
  .catch((error) => {
    console.error("Error fetching data:", error);
  });

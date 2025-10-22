import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

import TomSelect from "tom-select";
import "tom-select/dist/css/tom-select.css";

// Inicialización
document.addEventListener("DOMContentLoaded", () => {
    new TomSelect("#computers", {
        plugins: ["remove_button"],
        create: false,
        maxItems: null,
        placeholder: "Selecciona los equipos asignados...",
        render: {
            option: function (data, escape) {
                return `<div class="flex items-center gap-2"><i class="fas fa-desktop text-purple-500"></i> ${escape(data.text)}</div>`;
            },
            item: function (data, escape) {
                return `<div class="flex items-center gap-2 bg-purple-100 px-2 py-1 rounded">${escape(data.text)}</div>`;
            }
        }
    });
});
document.addEventListener('DOMContentLoaded', function () {
    new TomSelect('#user_id_select', {
        create: false,
        sortField: {
            field: "text",
            direction: "asc"
        },
        placeholder: 'Buscar usuario...',
        maxOptions: 1000,
        allowEmptyOption: true,
        searchField: ['text'],
        render: {
            option: function (data, escape) {
                return `<div class='flex items-center gap-2 px-4 py-2 text-gray-800'>
                            <i class='fas fa-user text-blue-400'></i> ${escape(data.text)}
                        </div>`;
            },
            item: function (data, escape) {
                return `<div class='flex items-center gap-2 bg-blue-100 px-2 py-1 rounded text-gray-800'>
                            <i class='fas fa-user text-blue-400'></i> ${escape(data.text)}
                        </div>`;
            }
        },
        onInitialize: function () {
            // Agregar clases Tailwind al control principal
            this.control.classList.add('w-full', 'px-4', 'py-2', 'border', 'rounded-lg', 'bg-white', 'text-gray-800', 'focus:border-indigo-500', 'focus:ring-2', 'focus:ring-indigo-100', 'transition-all', 'duration-200', 'shadow-sm');
        }
    });
});

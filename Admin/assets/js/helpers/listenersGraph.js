/**
 Wordpress Radio (wpradio) - a radio streaming platform for wordpress
 Copyright (C) 2020 Caster.fm (www.caster.fm)

 This program is free software; you can redistribute it and/or
 modify it under the terms of the GNU General Public License
 as published by the Free Software Foundation; either version 2
 of the License, or (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

import { accountStore } from './stores';
import Alpine from 'alpinejs';
import { Chart, LineController, LineElement, PointElement, LinearScale, TimeScale, Tooltip, Legend } from 'chart.js';
import 'chartjs-adapter-date-fns';

const createListenersGraph = () => {


  Alpine.effect(() => {
    const serverData = Alpine.store('server').data;

    if (window.wpradioComponenets.listenersGraph) {
      if (serverData !== null) {
        let onlineMounts = serverData.source;
        let time = new Date();

        window.wpradioComponenets.listenersGraph.data.datasets.forEach((dataSet, i) => {
          var meta = window.wpradioComponenets.listenersGraph.getDatasetMeta(i);
          meta.hidden = !onlineMounts.hasOwnProperty(dataSet.id);
        });

        _.each(onlineMounts, function(value, key) {
          let mountDatasetIndex = _.findIndex(window.wpradioComponenets.listenersGraph.data.datasets, {
            id: key,
          });
          window.wpradioComponenets.listenersGraph.data.datasets[mountDatasetIndex].data.push({
            x: time,
            y: value.listeners,
          });
        });
      } else {
        window.wpradioComponenets.listenersGraph.data.datasets.forEach((dataSet, i) => {
          var meta = window.wpradioComponenets.listenersGraph.getDatasetMeta(i);
          meta.hidden = true;
        });
      }

      window.wpradioComponenets.listenersGraph.data.datasets.forEach((dataSet, i) => {
        if (dataSet.data.length > 120) {
          window.wpradioComponenets.listenersGraph.data.datasets[i].data.shift();
        }
      });

      window.wpradioComponenets.listenersGraph.update();
    }
  });

  const colors = {
    backgroundColor: [
      'rgba(255, 99, 132, 0.2)',
      'rgba(54, 162, 235, 0.2)',
      'rgba(255, 206, 86, 0.2)',
      'rgba(75, 192, 192, 0.2)',
      'rgba(153, 102, 255, 0.2)',
      'rgba(255, 159, 64, 0.2)',
      'rgba(102,255,140, 0.2)',
      'rgba(255,102,102, 0.2)',
      'rgba(166,255,102, 0.2)',
      'rgba(102,122,255, 0.2)',
    ],
    borderColor: [
      'rgb(255,99,193)',
      'rgba(54, 162, 235, 1)',
      'rgba(255, 206, 86, 1)',
      'rgb(75,192,192)',
      'rgba(153, 102, 255, 1)',
      'rgb(255,159,64)',
      'rgb(102,255,140)',
      'rgb(255,102,102)',
      'rgb(166,255,102)',
      'rgb(102,122,255)',
    ],
  };

  const getInitialDataSets = () => {
    let datasets = [];
    let index = 0;
    _.each(accountStore().data.channels, function(channel, key, list) {
      let dateNow = new Date();
      datasets.push({
        id: channel.streaming_server_mountpoint,
        label: channel.name,
        backgroundColor: colors.backgroundColor[index],
        borderColor: colors.borderColor[index],
        data: [],
        type: 'line',
        pointRadius: 3,
        fill: true,
        lineTension: 0,
        borderWidth: 2,
      });

      index++;
    });

    return datasets;
  };

  Chart.register(LineController, LineElement, PointElement, LinearScale, TimeScale, Tooltip, Legend);

  return (window.wpradioComponenets.listenersGraph = new Chart(document.getElementById('listeners-chart').getContext('2d'), {
    type: 'line',
    data: {
      datasets: getInitialDataSets(),
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,

      scales: {
        x: {
          display: true,
          type: 'time',
          distribution: 'linear',
          offset: true,
          bounds: 'data',
          ticks: {
            autoSkip: true,
            source: 'data',
            // sampleSize: 60,
            autoSkipPadding: 5,
          },
          time: {
            unit: 'minute',
            stepSize: 1,
            minUnit: 'minute',
          },
        },
        y: {
          type: 'linear',
          display: true,
          scaleLabel: {
            display: false,
            labelString: 'Listeners',
          },
          ticks: {
            autoSkip: true,
            beginAtZero: true,
            min: 0,
            stepSize: 1,
          },
        },
      },
      plugins: {
        tooltip: {
          intersect: true,
          mode: 'nearest',
          callbacks: {
            label: function(tooltipItem) {
              let label = tooltipItem.dataset.label || '';
              if (label) {
                label += ': ';
              }
              label += tooltipItem.formattedValue;
              return label;
            },
          },
        },
      },
    },
  }));
};

export default createListenersGraph;

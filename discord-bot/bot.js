import { Client, GatewayIntentBits, ButtonBuilder, ActionRowBuilder, ButtonStyle, Events } from 'discord.js';
import dotenv from 'dotenv';

// Load environment variables
dotenv.config();

// Initialize the Discord client
const client = new Client({ intents: [GatewayIntentBits.Guilds] });

// State management for users
const userState = new Map();

// Event listener when the bot is ready
client.once(Events.ClientReady, () => {
    console.log('Bot is online!');
});

client.on(Events.InteractionCreate, async interaction => {
    if (interaction.isCommand()) {
        const { commandName } = interaction;

        if (commandName === 'teams') {
            // Fetch teams from Laravel API
            const response = await fetch(process.env.APP_URL + '/api/bingo/1/teams'); // Adjust the URL as needed
            const teams = await response.json();

            // Create buttons for each team
            const buttons = teams.map(team => 
                new ButtonBuilder()
                    .setCustomId(`team_${team.id}`)
                    .setLabel(team.name)
                    .setStyle(ButtonStyle.Primary)
            );

            const row = new ActionRowBuilder().addComponents(buttons);

            // Store initial state
            userState.set(interaction.user.id, { stage: 1, selectedTeam: null });

            await interaction.reply({ content: 'Select a team:', components: [row] });
        }
    }

    if (interaction.isButton()) {
        const userId = interaction.user.id;
        const state = userState.get(userId);

        if (!state) {
            await interaction.reply('Please start the process by using the /teams command.');
            return;
        }

        const [prefix, teamId] = interaction.customId.split('_');

        if (prefix === 'team' && state.stage === 1) {
            // User selected a team
            state.selectedTeam = teamId;
            state.stage = 2;

            // Update the state
            userState.set(userId, state);

            // Follow-up question: For example, ask for a team member selection
            const tilesResponse = await fetch(`${process.env.APP_URL}/api/bingo/1/team/${teamId}/tiles`);
            const tiles = await tilesResponse.json();
            console.log(tiles);
            // Create buttons for each member
            const tilesButtons = tiles.map(tile => 
                
                new ButtonBuilder()
                    .setCustomId(`tile_${tile.id}`)
                    .setLabel(tile.task_name)
                    .setStyle(ButtonStyle.Secondary)
            );

            const tilesRows = [];
            for (let i = 0; i < tilesButtons.length; i += 5) {
                const row = new ActionRowBuilder().addComponents(tilesButtons.slice(i, i + 5));
                tilesRows.push(row);
            }

            console.log(tilesRows);
            await interaction.update({ content: `You selected Team ID: ${teamId}. Now, select a task:`, components: tilesRows });
        } else if (prefix === 'member' && state.stage === 2) {
            // User selected a team member
            const memberId = teamId; // reusing teamId variable for simplicity
            state.stage = 3;

            // Final stage or further questions can be handled here
            await interaction.update({ content: `You selected Member ID: ${memberId}. Process complete.`, components: [] });

            // Clear the state if done
            userState.delete(userId);
        }
    }
});

// Login to Discord with your bot token
client.login(process.env.DISCORD_BOT_TOKEN);

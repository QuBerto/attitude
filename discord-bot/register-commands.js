import { REST, Routes } from 'discord.js';
import dotenv from 'dotenv';

// Load environment variables
dotenv.config();

// Define your commands
const commands = [
    {
        name: 'ping',
        description: 'Replies with Pong!',
    },
    {
        name: 'teams',
        description: 'Lists all teams',
    },
    // Add more commands here
];

// Initialize the REST client
const rest = new REST({ version: '10' }).setToken(process.env.DISCORD_BOT_TOKEN);

// Register commands
(async () => {
    try {
        console.log('Started refreshing application (/) commands.');

        await rest.put(
            Routes.applicationCommands(process.env.DISCORD_CLIENT_ID), // Global registration
            // For guild-specific commands, use the following instead:
            // Routes.applicationGuildCommands(process.env.DISCORD_CLIENT_ID, process.env.DISCORD_GUILD_ID),
            { body: commands }
        );

        console.log('Successfully reloaded application (/) commands.');
    } catch (error) {
        console.error(error);
    }
})();

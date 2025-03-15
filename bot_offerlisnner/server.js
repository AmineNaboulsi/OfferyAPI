require('dotenv').config();
const { Client, GatewayIntentBits } = require('discord.js');
const axios = require('axios');

const client = new Client({
  intents: [
    GatewayIntentBits.Guilds,
    GatewayIntentBits.GuildMessages,
    GatewayIntentBits.MessageContent,
  ]
});

async function storeMessage(message) {
  try {
    const messageData = {
      messageId: message.id,
      content: message.content,
      authorId: message.author.id,
      authorUsername: message.author.username,
      channelId: message.channelId,
      guildId: message.guildId,
      timestamp: message.createdTimestamp
    };
    console.log('Storing message:', messageData);

    const response = await sendToOpenAI(messageData.content);
    console.log('OpenAI response:', response);

    return response;

  } catch (error) {
    console.error('Error storing message:', error.message);
    return null;
  }
}

async function sendToOpenAI(content) {
  const OPENAI_API_KEY = process.env.OPENAI_API_KEY;
  const OPENAI_API_URL = 'https://api.openai.com/v1/chat/completions';
  console.log('Sending to OpenAI:');
  try {
    const response = await fetch(OPENAI_API_URL, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${OPENAI_API_KEY}`
      },
      body: JSON.stringify({
        model: 'gpt-3.5-turbo',
        messages: [
          {
            role: 'user',
            content: content
          }
        ],
        max_tokens: 150
      })
    });

    if (!response.ok) {
      const errorData = await response.json();
      throw new Error(`OpenAI API error: ${errorData.error?.message || response.statusText}`);
    }

    const data = await response.json();
    return data.choices[0].message.content;
  } catch (error) {
    console.error('Error calling OpenAI API:', error);
    throw error;
  }
}


client.once('ready', () => {
  console.log(`Logged in as ${client.user.tag}`);
  console.log('Message listener is active!');
});

client.on('messageCreate', async (message) => {
  if (message.author.bot) return;

  const result = await storeMessage(message);
});

client.login(process.env.DISCORD_TOKEN);

client.on('error', (error) => {
  console.error('Discord client error:', error);
});

process.on('SIGINT', () => {
  console.log('Shutting down...');
  client.destroy();
  process.exit(0);
});

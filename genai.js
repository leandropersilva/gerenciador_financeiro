import { GoogleGenAI } from "@google/genai";

const ai = new GoogleGenAI({ apiKey: 'AIzaSyaCvHbeSVLsWydTxl6ALoXfL7bM7NLy17lA' });

const response = await ai.models.generateContent({
  model: "gemini-2.0-flash",
  contents: "como fazer café?"
});

console.log(response.text);
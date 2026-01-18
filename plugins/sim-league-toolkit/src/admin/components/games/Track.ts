import {Entity} from "../shared/Entity";
import {TrackLayout} from "./TrackLayout";

export interface Track extends Entity {
    country: string;
    countryCode: string;
    fullName: string;
    gameId: number;
    latitude?: number;
    layouts?: TrackLayout[];
    longitude?: number;
    shortName: string;
    trackId: string;
}